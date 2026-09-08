@extends('layouts.app', ['active' => 'blog'])

@section('title', 'Blog — Infinite Books Publishing')
@section('description', 'Writing tips, publishing advice and book marketing guidance from the Infinite Books Publishing team.')

@section('content')

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container">
      <p class="eyebrow"><span class="eyebrow-line"></span>The Blog</p>
      <h1>Writing &amp; Publishing <span class="accent">Advice</span></h1>
      <p>Practical guidance on ghostwriting, self-publishing and book marketing from the Infinite Books Publishing team.</p>
    </div>
  </section>

  <!-- ================= BLOG GRID ================= -->
  <section class="section">
    <div class="container">
      <div class="card-grid blog-grid">
        @foreach ($posts as $post)
          <a class="blog-card reveal" href="{{ route('blog.show', $post->slug) }}">
            <img src="{{ asset($post->cover) }}" alt="{{ $post->title }}">
            <div class="blog-card-body">
              <span class="blog-meta">{{ $post->category }} &middot; {{ $post->read_time }}</span>
              <h3>{{ $post->title }}</h3>
              <p>{{ $post->excerpt }}</p>
              <span class="blog-readmore">Read Article
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
              </span>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section class="section section-alt">
    <div class="container" style="text-align:center;">
      <p class="eyebrow center"><span class="eyebrow-line"></span>Have a Book in Progress?</p>
      <h2>Let's Talk About Your Project</h2>
      <p class="section-lede" style="margin-bottom:2rem;">Book a free consultation and we'll help you map out the path from draft to published book.</p>
      <a href="{{ route('contact.index') }}" class="btn btn-primary">Get a Free Consultation
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
      </a>
    </div>
  </section>

@endsection
