@extends('layouts.app', ['active' => 'services'])

@section('title', 'Our Services — Infinite Books Publishing')
@section('description', 'Ghostwriting, editing, cover design, publishing, marketing and audiobook production — everything you need to take a book from draft to bookstore shelf.')

@section('content')

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container">
      <p class="eyebrow"><span class="eyebrow-line"></span>What We Do</p>
      <h1>Every Step of Publishing, <span class="accent">Under One Roof</span></h1>
      <p>From a rough idea to a finished book in readers' hands — pick a single service or let us run the entire process.</p>
    </div>
  </section>

  <!-- ================= SERVICES GRID ================= -->
  <section class="section">
    <div class="container">
      <div class="card-grid services-grid">
        <a class="feature-card reveal" href="{{ route('services.show', 'ghostwriting') }}">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path></svg>
          </span>
          <h3>Book Writing &amp; Ghostwriting</h3>
          <p>Have a story but need the words? Our writers shape your ideas into a polished manuscript that sounds like you.</p>
          <span class="blog-readmore">Explore Ghostwriting
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </span>
        </a>
        <a class="feature-card reveal" href="{{ route('services.show', 'editing-proofreading') }}">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 5H3"></path><path d="M17 12H3"></path><path d="M21 19H3"></path><path d="m17 8 4 4-4 4"></path></svg>
          </span>
          <h3>Editing &amp; Proofreading</h3>
          <p>Line editing, structural feedback and meticulous proofreading so your book is error-free and reader-ready.</p>
          <span class="blog-readmore">Explore Editing
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </span>
        </a>
        <a class="feature-card reveal" href="{{ route('services.show', 'cover-design') }}">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5"></circle><circle cx="17.5" cy="10.5" r=".5"></circle><circle cx="8.5" cy="7.5" r=".5"></circle><circle cx="6.5" cy="12.5" r=".5"></circle><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path></svg>
          </span>
          <h3>Cover Design &amp; Illustration</h3>
          <p>Covers and interior artwork that capture your story at a glance — from children's books to literary fiction.</p>
          <span class="blog-readmore">Explore Cover Design
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </span>
        </a>
        <a class="feature-card reveal" href="{{ route('services.show', 'publishing-distribution') }}">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
          </span>
          <h3>Publishing &amp; Distribution</h3>
          <p>Formatting and release on Amazon, IngramSpark and other major platforms — print, e-book and beyond.</p>
          <span class="blog-readmore">Explore Publishing
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </span>
        </a>
        <a class="feature-card reveal" href="{{ route('services.show', 'book-marketing') }}">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>
          </span>
          <h3>Book Marketing</h3>
          <p>Bespoke launch campaigns, social promotion and PR support that put your book in front of the right readers.</p>
          <span class="blog-readmore">Explore Marketing
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </span>
        </a>
        <a class="feature-card reveal" href="{{ route('services.show', 'audiobook-production') }}">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 10v4"></path><path d="M6 6v12"></path><path d="M10 3v18"></path><path d="M14 8v9"></path><path d="M18 5v14"></path><path d="M22 10v4"></path></svg>
          </span>
          <h3>Audiobook Production</h3>
          <p>Professional narration, editing and mastering that brings your book to listeners everywhere.</p>
          <span class="blog-readmore">Explore Audiobooks
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </span>
        </a>
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section class="section section-alt">
    <div class="container" style="text-align:center;">
      <p class="eyebrow center"><span class="eyebrow-line"></span>Not Sure Where to Start?</p>
      <h2>Tell Us About Your Book</h2>
      <p class="section-lede" style="margin-bottom:2rem;">Share where your manuscript stands today and we'll recommend the right service or package.</p>
      <a href="{{ route('contact.index') }}" class="btn btn-primary">Get a Free Consultation
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
      </a>
    </div>
  </section>

@endsection
