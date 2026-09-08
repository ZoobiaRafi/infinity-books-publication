@extends('layouts.app', ['active' => 'blog'])

@section('title', $post->title.' — Infinite Books Publishing')
@section('description', $post->meta_description)

@section('content')

  <article class="section" style="padding-top:calc(var(--header-h) + 3rem);">
    <div class="container prose">
      <a href="{{ route('blog.index') }}" class="back-link">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"></path><path d="m12 19-7-7 7-7"></path></svg>
        Back to Blog
      </a>
      <p class="post-meta">
        <span>{{ $post->category }}</span>
        <span>&middot;</span>
        <span>{{ $post->read_time }}</span>
      </p>
      <h1>{{ $post->title }}</h1>

      <div class="post-cover">
        <img src="{{ asset($post->cover) }}" alt="{{ $post->title }}">
      </div>

      {!! $post->body !!}
    </div>
  </article>

  <!-- ================= CTA ================= -->
  <section class="section section-alt">
    <div class="container" style="text-align:center;">
      <p class="eyebrow center"><span class="eyebrow-line"></span>{{ $post->cta_eyebrow }}</p>
      <h2>{{ $post->cta_heading }}</h2>
      <p class="section-lede" style="margin-bottom:2rem;">{{ $post->cta_text }}</p>
      @if ($post->ctaService)
        <a href="{{ route('services.show', $post->ctaService->slug) }}" class="btn btn-primary">{{ $post->cta_button_text }}
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
        </a>
      @endif
    </div>
  </section>

@endsection
