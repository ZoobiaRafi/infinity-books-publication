@extends('layouts.app', ['active' => 'contact'])

@section('title', 'Contact Us — Infinite Books Publishing')
@section('description', "Get in touch with Infinite Books Publishing — call, email, or fill out our form and we'll respond within one business day.")

@section('content')

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container">
      <p class="eyebrow"><span class="eyebrow-line"></span>Get In Touch</p>
      <h1>Let's Talk About <span class="accent">Your Book</span></h1>
      <p>Call, email, or fill out the form below — we respond to every inquiry within one business day.</p>
    </div>
  </section>

  <!-- ================= CONTACT GRID ================= -->
  <section class="section">
    <div class="container contact-page-grid">
      <div class="reveal">
        <p class="eyebrow"><span class="eyebrow-line"></span>Contact Details</p>
        <h2>We'd Love to Hear About Your Project</h2>
        <p style="color:var(--fg-muted);margin:1.1rem 0 0;">Whether you have a finished manuscript or just an idea, tell us where things stand — we'll recommend the right service or package and outline next steps.</p>

        <div class="contact-info-list">
          <a class="contact-method" href="tel:9802234655">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.804 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
            </span>
            <span><strong>Give us a ring</strong><span>+1 (980) 223-4655</span></span>
          </a>
          <a class="contact-method" href="mailto:info@infinitebookspublishing.com">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
            </span>
            <span><strong>Drop us a line</strong><span>info@infinitebookspublishing.com</span></span>
          </a>
          <div class="contact-method" style="cursor:default;">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </span>
            <span><strong>Business hours</strong><span>Mon&ndash;Fri, 9am&ndash;6pm ET</span></span>
          </div>
        </div>
      </div>

      <div class="hero-form-card reveal">
        <h2>Start Your Publishing Journey</h2>
        <p>Fill out the form and our team will reach out to you within one business day.</p>
        @if (session('success'))
          <p class="form-success">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
          <p class="form-success" style="color:#e5484d;">{{ $errors->first() }}</p>
        @endif
        <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
          @csrf
          <label class="sr-only" for="contact-name">Full Name</label>
          <input id="contact-name" name="name" type="text" placeholder="Full Name" value="{{ old('name') }}" required>
          <label class="sr-only" for="contact-phone">Phone Number</label>
          <input id="contact-phone" name="phone" type="tel" placeholder="Phone Number" value="{{ old('phone') }}" required>
          <label class="sr-only" for="contact-email">Email Address</label>
          <input id="contact-email" name="email" type="email" placeholder="Email Address" value="{{ old('email') }}" required>
          <label class="sr-only" for="contact-service">Service of Interest</label>
          <select id="contact-service" name="service" required style="width:100%;padding:0.85rem 1rem;background:var(--bg-alt);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--fg);">
            <option value="" disabled {{ old('service') ? '' : 'selected' }}>Service of Interest</option>
            <option {{ old('service') === 'Ghostwriting' ? 'selected' : '' }}>Ghostwriting</option>
            <option {{ old('service') === 'Editing & Proofreading' ? 'selected' : '' }}>Editing &amp; Proofreading</option>
            <option {{ old('service') === 'Cover Design & Illustration' ? 'selected' : '' }}>Cover Design &amp; Illustration</option>
            <option {{ old('service') === 'Publishing & Distribution' ? 'selected' : '' }}>Publishing &amp; Distribution</option>
            <option {{ old('service') === 'Book Marketing' ? 'selected' : '' }}>Book Marketing</option>
            <option {{ old('service') === 'Audiobook Production' ? 'selected' : '' }}>Audiobook Production</option>
            <option {{ old('service') === 'Not sure yet' ? 'selected' : '' }}>Not sure yet</option>
          </select>
          <label class="sr-only" for="contact-message">About Your Project</label>
          <textarea id="contact-message" name="message" placeholder="About Your Project" rows="4" required>{{ old('message') }}</textarea>
          <button type="submit" class="btn btn-primary btn-block">Send Email</button>
        </form>
      </div>
    </div>
  </section>

@endsection
