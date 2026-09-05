@extends('layouts.app', ['active' => 'testimonials'])

@section('title', 'Testimonials — Infinite Books Publishing')
@section('description', 'What our authors say about working with Infinite Books Publishing — editing, ghostwriting, design, marketing and audiobook production.')

@section('content')

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container">
      <p class="eyebrow"><span class="eyebrow-line"></span>Happy Authors</p>
      <h1>What Our <span class="accent">Clients Say</span></h1>
      <p>Real feedback from authors we've worked with across ghostwriting, editing, design, marketing and audiobook production.</p>
    </div>
  </section>

  <!-- ================= REVIEWS GRID ================= -->
  <section class="section">
    <div class="container">
      <div class="card-grid reviews-grid">
        <article class="review-card reveal">
          <h3>Exceptional Editing &amp; Publishing</h3>
          <p>&ldquo;The editors refined my story while keeping my voice intact. The whole publishing process was smooth and transparent.&rdquo;</p>
          <footer><span class="avatar">PG</span><span><strong>Phillip G.D. Jones</strong><time>Mar 12, 2024</time></span></footer>
        </article>
        <article class="review-card reveal">
          <h3>Amazing Children's Book Illustration</h3>
          <p>&ldquo;The characters were vibrant and perfectly matched the tone of my story. Kids absolutely love the visuals.&rdquo;</p>
          <footer><span class="avatar">ST</span><span><strong>Sarah Thompson</strong><time>Jun 15, 2024</time></span></footer>
        </article>
        <article class="review-card reveal">
          <h3>Powerful Book Marketing Strategy</h3>
          <p>&ldquo;Their campaigns improved my book's visibility and boosted sales. I was impressed by their knowledge of the market.&rdquo;</p>
          <footer><span class="avatar">MR</span><span><strong>Michael Rodriguez</strong><time>Jan 16, 2024</time></span></footer>
        </article>
        <article class="review-card reveal">
          <h3>Great Support for First-Time Authors</h3>
          <p>&ldquo;The editorial team was patient, detailed and professional. I felt supported throughout the entire publishing journey.&rdquo;</p>
          <footer><span class="avatar">MG</span><span><strong>Maria Garcia</strong><time>Oct 3, 2024</time></span></footer>
        </article>
        <article class="review-card reveal">
          <h3>Professional Audiobook Production</h3>
          <p>&ldquo;Narration, editing and production were handled perfectly. The audiobook opened a completely new audience for my book.&rdquo;</p>
          <footer><span class="avatar">RL</span><span><strong>Robert Lee</strong><time>Oct 20, 2024</time></span></footer>
        </article>
        <article class="review-card reveal">
          <h3>A Publishing Team That Delivers</h3>
          <p>&ldquo;From our first call to the day my book went live on Amazon, I always knew what was happening and when. That mattered more than I expected.&rdquo;</p>
          <footer><span class="avatar">DK</span><span><strong>David Kim</strong><time>Feb 8, 2025</time></span></footer>
        </article>
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section class="section section-alt">
    <div class="container" style="text-align:center;">
      <p class="eyebrow center"><span class="eyebrow-line"></span>Join Our Authors</p>
      <h2>Ready to Write Your Own Success Story?</h2>
      <p class="section-lede" style="margin-bottom:2rem;">Tell us about your book and let's get started.</p>
      <a href="{{ route('contact.index') }}" class="btn btn-primary">Get a Free Consultation
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
      </a>
    </div>
  </section>

@endsection
