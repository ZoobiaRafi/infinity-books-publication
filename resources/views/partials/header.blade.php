@php($active = $active ?? null)
<header class="site-header" id="site-header">
  <div class="container header-inner">
    <a href="{{ route('home') }}" class="logo" aria-label="Infinite Books Publishing — home">
      <img src="{{ asset('assets/logo-header.png') }}" alt="Infinite Books Publishing" class="logo-img">
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
              @foreach ($navServices as $navService)
                <li>
                  <a href="{{ route('services.show', $navService->slug) }}">
                    <span class="dd-icon">{!! $navService->icon !!}</span>
                    <span class="dd-title">{{ $navService->nav_title }}</span>
                  </a>
                </li>
              @endforeach
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
        <img src="{{ asset('assets/logo-header.png') }}" alt="Infinite Books Publishing" class="logo-img">
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
          @foreach ($navServices as $navService)
            <li><a href="{{ route('services.show', $navService->slug) }}">{{ $navService->nav_title }}</a></li>
          @endforeach
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
