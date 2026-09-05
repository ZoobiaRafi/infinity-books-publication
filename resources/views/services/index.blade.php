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
        @foreach ($services as $item)
          <a class="feature-card reveal" href="{{ route('services.show', $item->slug) }}">
            <span class="feature-icon">
              {!! $item->icon !!}
            </span>
            <h3>{{ $item->nav_title }}</h3>
            <p>{{ $item->summary }}</p>
            <span class="blog-readmore">Explore {{ $item->nav_title }}
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </span>
          </a>
        @endforeach
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
