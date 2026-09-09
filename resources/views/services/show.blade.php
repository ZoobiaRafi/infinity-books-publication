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
      @if ($service['image'])
        <div class="info-image reveal">
          <img src="{{ $service['image_url'] }}" alt="{{ $service['image_alt'] }}">
        </div>
      @endif
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

  <!-- ================= RELATED WORK ================= -->
  @if (count($service['portfolioItems']))
    <section class="section">
      <div class="container">
        <div class="section-head reveal">
          <p class="eyebrow"><span class="eyebrow-line"></span>Related Work</p>
          <h2>See It In <span class="accent">Action</span></h2>
        </div>
        <div class="card-grid portfolio-grid">
          @foreach ($service['portfolioItems'] as $item)
            <figure class="portfolio-card reveal">
              <img src="{{ $item->image_url }}" alt="{{ $item->image_alt ?? $item->title }}" loading="lazy">
              <figcaption><span class="tag">{{ $item->category_label }}</span><span class="sub">{{ $item->subtitle }}</span></figcaption>
            </figure>
          @endforeach
        </div>
        <p style="text-align:center;margin-top:2rem;">
          <a href="{{ route('portfolio') }}" class="btn btn-outline">View Full Portfolio</a>
        </p>
      </div>
    </section>
  @endif

  <!-- ================= FAQ ================= -->
  @if (count($service['faqs']))
    <section class="section" id="faq">
      <div class="container container-narrow">
        <div class="section-head reveal">
          <p class="eyebrow center"><span class="eyebrow-line"></span>FAQ</p>
          <h2>Questions, <span class="accent">Answered</span></h2>
        </div>
        <div class="accordion reveal">
          @foreach ($service['faqs'] as $i => $faq)
            <div class="accordion-item {{ $i === 0 ? 'is-open' : '' }}">
              <button class="accordion-trigger" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                <span>{{ $faq['question'] }}</span>
                <span class="accordion-icon">+</span>
              </button>
              <div class="accordion-panel">
                <p>{{ $faq['answer'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
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
