@extends('layouts.app', ['active' => 'about'])

@section('title', 'About Us — Infinite Books Publishing')
@section('description', 'Infinite Books Publishing helps new and experienced authors turn ideas into professionally published books. Learn about our story, values and team.')

@section('content')

  <!-- ================= PAGE HERO ================= -->
  <section class="page-hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container">
      <p class="eyebrow"><span class="eyebrow-line"></span>About Us</p>
      <h1>Built by Authors, <span class="accent">for Authors</span></h1>
      <p>We started Infinite Books Publishing because too many great stories were stalling out between "finished draft" and "published book." Today we're the full-service team that closes that gap.</p>
    </div>
  </section>

  <!-- ================= STORY ================= -->
  <section class="section">
    <div class="container info-grid">
      <div class="reveal">
        <p class="eyebrow"><span class="eyebrow-line"></span>Our Story</p>
        <h2>A Publishing Partner That Actually Publishes</h2>
        <p style="color:var(--fg-muted);margin:1.1rem 0 1.1rem;">Infinite Books Publishing was founded on a simple frustration: authors kept finishing manuscripts and then getting stuck. Traditional publishers were slow and selective. Freelance marketplaces meant juggling five different specialists who'd never spoken to each other. So we built the alternative — one dedicated team that carries a book from first draft to bookstore shelf.</p>
        <p style="color:var(--fg-muted);margin-bottom:1.1rem;">Over the past decade we've grown into a team of editors, ghostwriters, designers and marketers pulled from trade publishing backgrounds, working across every genre from literary fiction to children's picture books. What hasn't changed is the model: one project manager, one point of contact, and a schedule you can actually plan around.</p>
        <p style="color:var(--fg-muted);">We've published over 700 titles for first-time authors, business leaders, memoirists and career novelists alike — and every one of them still owns 100% of their rights and royalties.</p>
      </div>
      <div class="info-image reveal">
        <img src="{{ asset('assets/images/hero-desk.jpg') }}" alt="An author's writing desk with manuscript, fountain pen and hardcover books">
      </div>
    </div>
  </section>

  <!-- ================= VALUES ================= -->
  <section class="section section-alt">
    <div class="container">
      <div class="section-head reveal">
        <p class="eyebrow center"><span class="eyebrow-line"></span>Our Values</p>
        <h2>What Guides Every Project</h2>
      </div>
      <div class="card-grid why-grid">
        <article class="feature-card reveal">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
          </span>
          <h3>Your Book, Your Rights</h3>
          <p>You retain full ownership of your manuscript, royalties and ISBN at every stage — no exceptions, no fine print.</p>
        </article>
        <article class="feature-card reveal">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"></path><circle cx="12" cy="8" r="6"></circle></svg>
          </span>
          <h3>Trade-Level Craft</h3>
          <p>Our editors and designers come from traditional publishing houses. Every manuscript gets treated like a lead title.</p>
        </article>
        <article class="feature-card reveal">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          </span>
          <h3>Honest Timelines</h3>
          <p>You get a published schedule with milestone check-ins from day one, so you always know what ships and when.</p>
        </article>
        <article class="feature-card reveal">
          <span class="feature-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </span>
          <h3>One Team, Not a Handoff</h3>
          <p>A single project manager plus your writer, editor, designer and marketer — everyone stays on your book start to finish.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- ================= STATS ================= -->
  <section class="section">
    <div class="container">
      <div class="stats-grid reveal">
        <div class="stat-card"><span class="stat-num">10+</span><span class="stat-label">Years of Experience</span></div>
        <div class="stat-card"><span class="stat-num">700+</span><span class="stat-label">Books Published</span></div>
        <div class="stat-card"><span class="stat-num">250+</span><span class="stat-label">Skilled Writers</span></div>
        <div class="stat-card"><span class="stat-num">100%</span><span class="stat-label">Rights Retained</span></div>
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section class="section section-alt">
    <div class="container" style="text-align:center;">
      <p class="eyebrow center"><span class="eyebrow-line"></span>Ready When You Are</p>
      <h2>Let's Bring Your Book to Life</h2>
      <p class="section-lede" style="margin-bottom:2rem;">Tell us where your manuscript stands today — we'll map out the rest.</p>
      <a href="{{ route('contact.index') }}" class="btn btn-primary">Start Your Story Today
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
      </a>
    </div>
  </section>

@endsection
