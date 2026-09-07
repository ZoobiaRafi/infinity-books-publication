document.addEventListener('DOMContentLoaded', () => {

  /* ---------- Sticky header shadow ---------- */
  const header = document.getElementById('site-header');
  const onScroll = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 12);
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* ---------- Mobile menu toggle (sidebar) ---------- */
  const menuToggle = document.getElementById('menu-toggle');
  const mobileNav = document.getElementById('mobile-nav');
  const mobileNavClose = document.getElementById('mobile-nav-close');
  const mobileNavBackdrop = document.getElementById('mobile-nav-backdrop');

  const openMobileMenu = () => {
    menuToggle.setAttribute('aria-expanded', 'true');
    mobileNav.classList.add('is-open');
    mobileNav.setAttribute('aria-hidden', 'false');
    mobileNavBackdrop.classList.add('is-open');
    document.body.classList.add('nav-open');
  };

  const closeMobileMenu = () => {
    menuToggle.setAttribute('aria-expanded', 'false');
    mobileNav.classList.remove('is-open');
    mobileNav.setAttribute('aria-hidden', 'true');
    mobileNavBackdrop.classList.remove('is-open');
    document.body.classList.remove('nav-open');
  };

  menuToggle.addEventListener('click', () => {
    if (mobileNav.classList.contains('is-open')) {
      closeMobileMenu();
    } else {
      openMobileMenu();
    }
  });

  mobileNavClose.addEventListener('click', closeMobileMenu);
  mobileNavBackdrop.addEventListener('click', closeMobileMenu);

  mobileNav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', closeMobileMenu);
  });

  /* ---------- Services dropdown (desktop + mobile) ---------- */
  function setupDropdown(toggleId) {
    const toggle = document.getElementById(toggleId);
    if (!toggle) return;
    const parent = toggle.closest('.has-dropdown');

    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = parent.getAttribute('data-open') === 'true';
      parent.setAttribute('data-open', String(!isOpen));
      toggle.setAttribute('aria-expanded', String(!isOpen));
    });
  }
  setupDropdown('services-toggle');
  setupDropdown('services-toggle-mobile');

  document.addEventListener('click', (e) => {
    document.querySelectorAll('.has-dropdown[data-open="true"]').forEach(el => {
      if (!el.contains(e.target)) {
        el.setAttribute('data-open', 'false');
        const btn = el.querySelector('.nav-dropdown-toggle');
        if (btn) btn.setAttribute('aria-expanded', 'false');
      }
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeMobileMenu();
      document.querySelectorAll('.has-dropdown[data-open="true"]').forEach(el => el.setAttribute('data-open', 'false'));
    }
  });

  /* ---------- FAQ accordion ---------- */
  document.querySelectorAll('.accordion-item').forEach(item => {
    const trigger = item.querySelector('.accordion-trigger');
    trigger.addEventListener('click', () => {
      const isOpen = item.classList.contains('is-open');
      item.classList.toggle('is-open', !isOpen);
      trigger.setAttribute('aria-expanded', String(!isOpen));
    });
  });

  /* ---------- Portfolio filter + show more/less ---------- */
  const filterBar = document.getElementById('filter-bar');
  if (filterBar) {
    const cards = document.querySelectorAll('#portfolio-full-grid .portfolio-card');
    const toggleBtn = document.getElementById('portfolio-toggle-btn');
    const visibleLimit = 6;
    let currentFilter = 'all';
    let expanded = false;

    const applyVisibility = () => {
      const matching = Array.from(cards).filter(card => currentFilter === 'all' || card.dataset.category === currentFilter);
      matching.forEach((card, i) => {
        card.hidden = !expanded && i >= visibleLimit;
      });
      cards.forEach(card => {
        if (!matching.includes(card)) card.hidden = true;
      });
      if (toggleBtn) {
        toggleBtn.hidden = matching.length <= visibleLimit;
        toggleBtn.textContent = expanded ? 'Show Less' : 'Show More';
      }
    };

    filterBar.addEventListener('click', (e) => {
      const btn = e.target.closest('.filter-btn');
      if (!btn) return;
      filterBar.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      currentFilter = btn.dataset.filter;
      expanded = false;
      applyVisibility();
    });

    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        expanded = !expanded;
        applyVisibility();
        if (!expanded) toggleBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      });
    }

    applyVisibility();
  }

  /* ---------- Reveal-on-scroll animation ---------- */
  const revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
    revealEls.forEach(el => io.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('is-visible'));
  }

  /* ---------- Contact forms ---------- */
  document.querySelectorAll('[data-form]').forEach(form => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const successMsg = form.querySelector('.form-success');
      if (successMsg) {
        successMsg.hidden = false;
      }
      form.reset();
      if (successMsg) {
        clearTimeout(form._successTimer);
        form._successTimer = setTimeout(() => { successMsg.hidden = true; }, 6000);
      }
    });
  });

  /* ---------- Disable submit button while the email is sending ---------- */
  document.querySelectorAll('.contact-form').forEach(form => {
    form.addEventListener('submit', () => {
      const btn = form.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        btn.textContent = 'Sending…';
      }
    });
  });

  /* ---------- Footer year ---------- */
  const yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

});
