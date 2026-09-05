@extends('layouts.app', ['active' => 'services'])

@section('title', $service['nav_title'].' Services — Infinite Books Publishing')
@section('description', $service['meta_description'])

@section('content')

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container">
      <p class="eyebrow"><span class="eyebrow-line"></span>Service</p>
      <h1>{{ $service['title'] }}<span class="accent">{{ $service['title_accent'] }}</span></h1>
      <p>{{ $service['lede'] }}</p>
      <div class="page-hero-actions">
        <a href="{{ route('contact.index') }}" class="btn btn-primary">Get a Free Consultation</a>
        <a href="tel:9802234655" class="btn btn-outline">Call Now</a>
      </div>
    </div>
  </section>

  <!-- ================= WHAT'S INCLUDED ================= -->
  <section class="section">
    <div class="container info-grid">
      <div class="reveal">
        <p class="eyebrow"><span class="eyebrow-line"></span>What's Included</p>
        <h2>{{ $service['included_heading'] }}</h2>
        <p style="color:var(--fg-muted);margin:1.1rem 0 1.6rem;">{{ $service['included_text'] }}</p>
        <ul class="check-list">
          @foreach ($service['included_list_array'] as $item)
            <li>{{ $item }}</li>
          @endforeach
        </ul>
      </div>
      <div class="info-image reveal">
        <img src="{{ asset($service['image']) }}" alt="{{ $service['image_alt'] }}">
      </div>
    </div>
  </section>

  <!-- ================= PROCESS ================= -->
  <section class="section section-alt">
    <div class="container" style="max-width:760px;">
      <div class="section-head left reveal">
        <p class="eyebrow"><span class="eyebrow-line"></span>How It Works</p>
        <h2>{{ $service['process_heading'] }}</h2>
      </div>
      <ul class="step-list reveal">
        @foreach ($service['steps_array'] as $i => $step)
          <li>
            <span class="step-num">{{ $i + 1 }}</span>
            <div><h4>{{ $step[0] }}</h4><p>{{ $step[1] }}</p></div>
          </li>
        @endforeach
      </ul>
    </div>
  </section>

  <!-- ================= TESTIMONIAL ================= -->
  @if ($service['testimonial'])
    <section class="section">
      <div class="container" style="max-width:640px;">
        <article class="review-card reveal">
          <h3>{{ $service['testimonial']['title'] }}</h3>
          <p>&ldquo;{{ $service['testimonial']['quote'] }}&rdquo;</p>
          <footer>
            <span class="avatar">{{ $service['testimonial']['initials'] }}</span>
            <span><strong>{{ $service['testimonial']['name'] }}</strong><time>{{ $service['testimonial']['date'] }}</time></span>
          </footer>
        </article>
      </div>
    </section>
  @endif

  <!-- ================= CTA ================= -->
  <section class="section section-alt">
    <div class="container" style="text-align:center;">
      <p class="eyebrow center"><span class="eyebrow-line"></span>{{ $service['cta_eyebrow'] }}</p>
      <h2>{{ $service['cta_heading'] }}</h2>
      <p class="section-lede" style="margin-bottom:2rem;">{{ $service['cta_text'] }}</p>
      <a href="{{ route('contact.index') }}" class="btn btn-primary">Get a Free Consultation
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
      </a>
    </div>
  </section>

@endsection
