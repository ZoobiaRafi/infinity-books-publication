(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const config = window.chatConfig || {};
    const widget = document.getElementById('chat-widget');
    if (!widget || !config.sendUrl || !config.pollUrl) return;

    const bubble = document.getElementById('chat-bubble');
    const panel = document.getElementById('chat-panel');
    const closeBtn = document.getElementById('chat-panel-close');
    const body = document.getElementById('chat-panel-body');
    const form = document.getElementById('chat-panel-form');
    const input = document.getElementById('chat-input');
    const badge = document.getElementById('chat-unread-badge');

    let lastId = 0;
    let isOpen = false;
    let unread = 0;

    function escapeHtml(str) {
      const div = document.createElement('div');
      div.textContent = str;
      return div.innerHTML;
    }

    function appendMessage(sender, text, scroll = true) {
      const wrap = document.createElement('div');
      wrap.className = 'chat-msg ' + sender;
      wrap.innerHTML = '<div class="chat-bubble-text">' + escapeHtml(text) + '</div>';
      body.appendChild(wrap);
      if (scroll) body.scrollTop = body.scrollHeight;
    }

    function setUnread(n) {
      unread = n;
      if (unread > 0 && !isOpen) {
        badge.hidden = false;
        badge.textContent = String(unread);
      } else {
        badge.hidden = true;
      }
    }

    function openPanel() {
      isOpen = true;
      panel.classList.add('is-open');
      panel.setAttribute('aria-hidden', 'false');
      bubble.setAttribute('aria-expanded', 'true');
      bubble.classList.add('is-open');
      setUnread(0);
      input.focus();
    }

    function closePanel() {
      isOpen = false;
      panel.classList.remove('is-open');
      panel.setAttribute('aria-hidden', 'true');
      bubble.setAttribute('aria-expanded', 'false');
      bubble.classList.remove('is-open');
    }

    bubble.addEventListener('click', () => {
      if (isOpen) {
        closePanel();
      } else {
        openPanel();
      }
    });
    closeBtn.addEventListener('click', closePanel);

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const text = input.value.trim();
      if (!text) return;

      appendMessage('visitor', text);
      input.value = '';
      input.style.height = 'auto';

      fetch(config.sendUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': config.csrfToken,
        },
        body: JSON.stringify({ body: text }),
      })
        .then((r) => r.json())
        .then((data) => {
          if (data.message && data.message.id > lastId) {
            lastId = data.message.id;
          }
        })
        .catch(() => {});
    });

    input.addEventListener('input', () => {
      input.style.height = 'auto';
      input.style.height = Math.min(input.scrollHeight, 96) + 'px';
    });

    function poll() {
      fetch(config.pollUrl + '?after=' + lastId, { headers: { Accept: 'application/json' } })
        .then((r) => r.json())
        .then((data) => {
          const messages = data.messages || [];
          if (!messages.length) return;

          let newUnread = 0;
          messages.forEach((m) => {
            appendMessage(m.sender, m.body, isOpen);
            lastId = m.id;
            if (m.sender === 'admin' && !isOpen) newUnread += 1;
          });

          if (newUnread > 0) setUnread(unread + newUnread);
        })
        .catch(() => {});
    }

    poll();
    setInterval(poll, 5000);
  });
})();
