@php($active = $active ?? null)
<header class="site-header" id="site-header">
  <div class="container header-inner">
    <a href="{{ route('home') }}" class="logo" aria-label="Infinite Books Publishing — home">
      <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 7v14"></path>
        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
      </svg>
      <span class="logo-text">Infinite Books <span class="accent">Publishing</span></span>
    </a>

    <nav class="main-nav" id="main-nav" aria-label="Primary">
      <ul>
        <li><a href="{{ route('home') }}" class="{{ $active === 'home' ? 'is-active' : '' }}">Home</a></li>
        <li><a href="{{ route('about') }}" class="{{ $active === 'about' ? 'is-active' : '' }}">About</a></li>
        <li class="has-dropdown">
          <button class="nav-dropdown-toggle" id="services-toggle" aria-expanded="false" aria-controls="services-menu">
            Services
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </button>
          <div class="dropdown-menu" id="services-menu">
            <ul class="dropdown-grid">
              <li>
                <a href="{{ route('services.show', 'ghostwriting') }}">
                  <span class="dd-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path></svg></span>
                  <span class="dd-title">Ghostwriting</span>
                </a>
              </li>
              <li>
                <a href="{{ route('services.show', 'editing-proofreading') }}">
                  <span class="dd-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 5H3"></path><path d="M17 12H3"></path><path d="M21 19H3"></path></svg></span>
                  <span class="dd-title">Editing &amp; Proofreading</span>
                </a>
              </li>
              <li>
                <a href="{{ route('services.show', 'cover-design') }}">
                  <span class="dd-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5"></circle><circle cx="17.5" cy="10.5" r=".5"></circle><circle cx="8.5" cy="7.5" r=".5"></circle><circle cx="6.5" cy="12.5" r=".5"></circle><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path></svg></span>
                  <span class="dd-title">Cover Design &amp; Illustration</span>
                </a>
              </li>
              <li>
                <a href="{{ route('services.show', 'publishing-distribution') }}">
                  <span class="dd-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg></span>
                  <span class="dd-title">Publishing &amp; Distribution</span>
                </a>
              </li>
              <li>
                <a href="{{ route('services.show', 'book-marketing') }}">
                  <span class="dd-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg></span>
                  <span class="dd-title">Book Marketing</span>
                </a>
              </li>
              <li>
                <a href="{{ route('services.show', 'audiobook-production') }}">
                  <span class="dd-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 10v4"></path><path d="M6 6v12"></path><path d="M10 3v18"></path><path d="M14 8v9"></path><path d="M18 5v14"></path><path d="M22 10v4"></path></svg></span>
                  <span class="dd-title">Audiobook Production</span>
                </a>
              </li>
            </ul>
            <a href="{{ route('services.index') }}" class="dd-all">View All Services →</a>
          </div>
        </li>
        <li><a href="{{ route('portfolio') }}" class="{{ $active === 'portfolio' ? 'is-active' : '' }}">Portfolio</a></li>
        <li><a href="{{ route('testimonials') }}" class="{{ $active === 'testimonials' ? 'is-active' : '' }}">Testimonials</a></li>
        <li><a href="{{ route('blog.index') }}" class="{{ $active === 'blog' ? 'is-active' : '' }}">Blog</a></li>
        <li><a href="{{ route('contact.index') }}" class="{{ $active === 'contact' ? 'is-active' : '' }}">Contact</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <a class="phone-link" href="tel:9802234655">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.804 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
        +1 (980) 223-4655
      </a>
      <a class="btn btn-primary btn-sm header-cta" href="{{ route('contact.index') }}">Free Consultation</a>
      <button class="menu-toggle" id="menu-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

{{-- Rendered outside <header> on purpose: .site-header uses backdrop-filter,
     which creates a new containing block for position:fixed descendants —
     nesting the sidebar inside it would confine top:0/bottom:0 to the
     header's own height instead of the viewport. --}}
<div class="mobile-nav-backdrop" id="mobile-nav-backdrop"></div>

<div class="mobile-nav" id="mobile-nav" aria-hidden="true">
    <div class="mobile-nav-head">
      <a href="{{ route('home') }}" class="logo">
        <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M12 7v14"></path>
          <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
        </svg>
        <span class="logo-text">Infinite Books <span class="accent">Publishing</span></span>
      </a>
      <button class="mobile-nav-close" id="mobile-nav-close" aria-label="Close menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
      </button>
    </div>
    <ul>
      <li><a href="{{ route('home') }}" class="{{ $active === 'home' ? 'is-active' : '' }}">Home</a></li>
      <li><a href="{{ route('about') }}" class="{{ $active === 'about' ? 'is-active' : '' }}">About</a></li>
      <li class="has-dropdown">
        <button class="nav-dropdown-toggle" id="services-toggle-mobile" aria-expanded="false" aria-controls="services-menu-mobile">
          Services
          <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        <ul class="dropdown-menu" id="services-menu-mobile">
          <li><a href="{{ route('services.show', 'ghostwriting') }}">Ghostwriting</a></li>
          <li><a href="{{ route('services.show', 'editing-proofreading') }}">Editing &amp; Proofreading</a></li>
          <li><a href="{{ route('services.show', 'cover-design') }}">Cover Design &amp; Illustration</a></li>
          <li><a href="{{ route('services.show', 'publishing-distribution') }}">Publishing &amp; Distribution</a></li>
          <li><a href="{{ route('services.show', 'book-marketing') }}">Book Marketing</a></li>
          <li><a href="{{ route('services.show', 'audiobook-production') }}">Audiobook Production</a></li>
          <li><a href="{{ route('services.index') }}">View All Services</a></li>
        </ul>
      </li>
      <li><a href="{{ route('portfolio') }}" class="{{ $active === 'portfolio' ? 'is-active' : '' }}">Portfolio</a></li>
      <li><a href="{{ route('testimonials') }}" class="{{ $active === 'testimonials' ? 'is-active' : '' }}">Testimonials</a></li>
      <li><a href="{{ route('blog.index') }}" class="{{ $active === 'blog' ? 'is-active' : '' }}">Blog</a></li>
      <li><a href="{{ route('contact.index') }}" class="{{ $active === 'contact' ? 'is-active' : '' }}">Contact</a></li>
      <li><a class="btn btn-primary" href="{{ route('contact.index') }}">Free Consultation</a></li>
    </ul>
</div>
