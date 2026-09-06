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
        @foreach ($services as $service)
          <button class="filter-btn" data-filter="{{ $service->slug }}">{{ $service->nav_title }}</button>
        @endforeach
      </div>

      <div class="card-grid portfolio-grid" id="portfolio-full-grid">
        @foreach ($portfolioItems as $item)
          <figure class="portfolio-card reveal" data-category="{{ $item->service->slug ?? '' }}">
            <img src="{{ asset($item->image) }}" alt="{{ $item->image_alt ?? $item->title }}" loading="lazy">
            <figcaption><span class="tag">{{ $item->category_label }}</span><span class="sub">{{ $item->subtitle }}</span></figcaption>
          </figure>
        @endforeach
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
