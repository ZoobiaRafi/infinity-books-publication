@extends('layouts.app', ['active' => 'portfolio'])

@section('title', 'Portfolio — Infinite Books Publishing')
@section('description', "Fiction, non-fiction, children's books, horror, memoirs and audiobooks — browse our published titles across every genre.")

@section('content')

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container">
      <p class="eyebrow"><span class="eyebrow-line"></span>Our Shelf</p>
      <h1>Discover Our <span class="accent">Portfolio</span></h1>
      <p>Fiction, non-fiction, children's books, horror, memoirs and audiobooks — every genre gets the Infinite treatment.</p>
    </div>
  </section>

  <!-- ================= FILTERABLE GRID ================= -->
  <section class="section">
    <div class="container">
      <div class="filter-bar" id="filter-bar">
        <button class="filter-btn is-active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="fiction">Fiction</button>
        <button class="filter-btn" data-filter="non-fiction">Non-Fiction</button>
        <button class="filter-btn" data-filter="children">Children's Books</button>
        <button class="filter-btn" data-filter="horror">Horror</button>
        <button class="filter-btn" data-filter="audiobooks">Audiobooks</button>
        <button class="filter-btn" data-filter="memoir">Memoir</button>
      </div>

      <div class="card-grid portfolio-grid" id="portfolio-full-grid">
        <figure class="portfolio-card reveal" data-category="fiction">
          <img src="{{ asset('assets/images/book-fiction.jpg') }}" alt="Fiction book cover — Literary &amp; Contemporary" loading="lazy">
          <figcaption><span class="tag">Fiction</span><span class="sub">Literary &amp; Contemporary</span></figcaption>
        </figure>
        <figure class="portfolio-card reveal" data-category="non-fiction">
          <img src="{{ asset('assets/images/book-nonfiction.jpg') }}" alt="Non-Fiction book cover — Business &amp; Self-Development" loading="lazy">
          <figcaption><span class="tag">Non-Fiction</span><span class="sub">Business &amp; Self-Development</span></figcaption>
        </figure>
        <figure class="portfolio-card reveal" data-category="children">
          <img src="{{ asset('assets/images/book-children.jpg') }}" alt="Children's Books book cover — Illustrated Picture Books" loading="lazy">
          <figcaption><span class="tag">Children's Books</span><span class="sub">Illustrated Picture Books</span></figcaption>
        </figure>
        <figure class="portfolio-card reveal" data-category="horror">
          <img src="{{ asset('assets/images/book-horror.jpg') }}" alt="Horror book cover — Thriller &amp; Suspense" loading="lazy">
          <figcaption><span class="tag">Horror</span><span class="sub">Thriller &amp; Suspense</span></figcaption>
        </figure>
        <figure class="portfolio-card reveal" data-category="audiobooks">
          <img src="{{ asset('assets/images/book-audio.jpg') }}" alt="Audiobooks book cover — Full Audio Production" loading="lazy">
          <figcaption><span class="tag">Audiobooks</span><span class="sub">Full Audio Production</span></figcaption>
        </figure>
        <figure class="portfolio-card reveal" data-category="memoir">
          <img src="{{ asset('assets/images/book-memoir.jpg') }}" alt="Memoir book cover — Biography &amp; Life Stories" loading="lazy">
          <figcaption><span class="tag">Memoir</span><span class="sub">Biography &amp; Life Stories</span></figcaption>
        </figure>
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section class="section section-alt">
    <div class="container" style="text-align:center;">
      <p class="eyebrow center"><span class="eyebrow-line"></span>Your Book Could Be Next</p>
      <h2>Let's Add Your Title to the Shelf</h2>
      <p class="section-lede" style="margin-bottom:2rem;">Tell us about your project and we'll map out the path from draft to published book.</p>
      <a href="{{ route('contact.index') }}" class="btn btn-primary">Get a Free Consultation
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
      </a>
    </div>
  </section>

@endsection
