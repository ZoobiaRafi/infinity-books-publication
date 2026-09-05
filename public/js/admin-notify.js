(function () {
  var POLL_URL = '/admin/chats/unread-check';
  var POLL_INTERVAL = 6000;
  var lastNotifiedId = null; // null = not yet initialised (first poll is a baseline, no alert)
  var audioCtx = null;
  var audioUnlocked = false;

  function getAudioContext() {
    if (audioCtx) return audioCtx;
    var Ctx = window.AudioContext || window.webkitAudioContext;
    if (!Ctx) return null;
    audioCtx = new Ctx();
    return audioCtx;
  }

  // Most browsers block audio until the page has had a user gesture.
  // Try immediately; if suspended, resume on the visitor's first interaction.
  function unlockAudioOnGesture() {
    if (audioUnlocked) return;
    var ctx = getAudioContext();
    if (ctx && ctx.state === 'suspended') {
      ctx.resume().then(function () { audioUnlocked = true; }).catch(function () {});
    } else {
      audioUnlocked = true;
    }
  }
  ['click', 'keydown', 'touchstart'].forEach(function (evt) {
    document.addEventListener(evt, unlockAudioOnGesture, { once: true, passive: true });
  });

  function playChime() {
    var ctx = getAudioContext();
    if (!ctx) return;

    var notes = [880, 1175]; // a short two-tone "ding-dong" style chime
    notes.forEach(function (freq, i) {
      var osc = ctx.createOscillator();
      var gain = ctx.createGain();
      osc.type = 'sine';
      osc.frequency.value = freq;
      var start = ctx.currentTime + i * 0.14;
      gain.gain.setValueAtTime(0, start);
      gain.gain.linearRampToValueAtTime(0.18, start + 0.02);
      gain.gain.exponentialRampToValueAtTime(0.0001, start + 0.32);
      osc.connect(gain);
      gain.connect(ctx.destination);
      osc.start(start);
      osc.stop(start + 0.34);
    });
  }

  function ensureNotificationPermission() {
    if (!('Notification' in window)) return;
    if (Notification.permission === 'default') {
      Notification.requestPermission().catch(function () {});
    }
  }

  function showBrowserNotification(latest) {
    if (!('Notification' in window) || Notification.permission !== 'granted') return false;
    try {
      var n = new Notification('New message from ' + latest.name, {
        body: latest.body,
        tag: 'chat-' + latest.id,
      });
      n.onclick = function () {
        window.focus();
        window.location.href = '/admin/chats/' + latest.conversation_id;
        n.close();
      };
      return true;
    } catch (e) {
      return false;
    }
  }

  function showToast(latest) {
    var toast = document.createElement('div');
    toast.textContent = 'New message from ' + latest.name + ': ' + latest.body;
    toast.setAttribute('role', 'status');
    Object.assign(toast.style, {
      position: 'fixed',
      top: '16px',
      right: '16px',
      zIndex: '99999',
      maxWidth: '340px',
      background: '#22A7F0',
      color: '#fff',
      padding: '12px 16px',
      borderRadius: '6px',
      boxShadow: '0 8px 24px rgba(0,0,0,0.25)',
      font: '13px/1.4 "Open Sans", Arial, sans-serif',
      cursor: 'pointer',
      transition: 'opacity 0.3s ease, transform 0.3s ease',
      opacity: '0',
      transform: 'translateY(-8px)',
    });
    toast.addEventListener('click', function () {
      window.location.href = '/admin/chats/' + latest.conversation_id;
    });
    document.body.appendChild(toast);
    requestAnimationFrame(function () {
      toast.style.opacity = '1';
      toast.style.transform = 'translateY(0)';
    });
    setTimeout(function () {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-8px)';
      setTimeout(function () { toast.remove(); }, 320);
    }, 6000);
  }

  function poll() {
    fetch(POLL_URL, { headers: { Accept: 'application/json' } })
      .then(function (r) { return r.ok ? r.json() : null; })
      .then(function (data) {
        if (!data) return;

        if (lastNotifiedId === null) {
          // First check after page load: just record the baseline (0 if
          // there's nothing unread yet), don't alert for messages that were
          // already unread before this page opened. This must run exactly
          // once regardless of whether data.latest exists yet — otherwise a
          // page that loads with zero unread messages never sets a baseline,
          // and the first genuinely new message gets silently swallowed
          // instead of triggering an alert.
          lastNotifiedId = data.latest ? data.latest.id : 0;
          return;
        }

        if (data.latest && data.latest.id > lastNotifiedId) {
          lastNotifiedId = data.latest.id;
          playChime();
          var shown = showBrowserNotification(data.latest);
          if (!shown) showToast(data.latest);
        }
      })
      .catch(function () {});
  }

  document.addEventListener('DOMContentLoaded', function () {
    ensureNotificationPermission();
    poll();
    setInterval(poll, POLL_INTERVAL);
  });
})();
