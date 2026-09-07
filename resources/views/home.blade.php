@extends('layouts.app', ['active' => 'home'])

@section('title', 'Infinite Books Publishing — Turn Your Ideas into Bestselling Books')
@section('description', 'From first draft to bookstore shelf — writing, editing, design, publishing and marketing under one roof. A trusted US publishing company.')

@section('content')

  <!-- ================= HERO ================= -->
  <section class="hero" id="top">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="container hero-grid">
      <div class="hero-copy">
        <p class="eyebrow"><span class="eyebrow-line"></span>A Trusted US Publishing Company</p>
        <h1>Turn Your Ideas into <span class="accent">Bestselling Books</span></h1>
        <p class="hero-lede">From first draft to bookstore shelf — writing, editing, design, publishing and marketing under one roof, so you can focus on what matters most: your story.</p>
        <div class="hero-actions">
          <a href="{{ route('contact.index') }}" class="btn btn-primary">Start Your Story Today
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </a>
          <a href="tel:9802234655" class="btn btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.804 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
            Call Now
          </a>
        </div>
        <div class="hero-image">
          <img src="{{ asset('assets/images/hero-desk.jpg') }}" alt="An author's writing desk with manuscript, fountain pen and hardcover books" loading="eager">
        </div>
      </div>

      <div class="hero-form-card" id="contact">
        <h2>Get in Touch</h2>
        <p>Tell us about your project and we'll get back within one business day.</p>
        @if (session('success'))
          <p class="form-success">{{ session('success') }}</p>
        @endif
        <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
          @csrf
          <label class="sr-only" for="hero-name">Full Name</label>
          <input id="hero-name" name="name" type="text" placeholder="Full Name" value="{{ old('name') }}" required>
          <label class="sr-only" for="hero-phone">Phone Number</label>
          <input id="hero-phone" name="phone" type="tel" placeholder="Phone Number" value="{{ old('phone') }}" required>
          <label class="sr-only" for="hero-email">Email Address</label>
          <input id="hero-email" name="email" type="email" placeholder="Email Address" value="{{ old('email') }}" required>
          <label class="sr-only" for="hero-message">About Your Project</label>
          <textarea id="hero-message" name="message" placeholder="About Your Project" rows="4" required>{{ old('message') }}</textarea>
          <button type="submit" class="btn btn-primary btn-block">Send Email</button>
        </form>
      </div>
    </div>
  </section>

  <!-- ================= TRUSTED BY ================= -->
  <section class="trusted-strip" aria-label="As featured in">
    <div class="marquee">
      <ul class="marquee-track">
        <li>NBC</li><li>BBC</li><li>Forbes</li><li>Newsweek</li><li>Politico</li><li>Inc.</li><li>Esquire</li><li>LA Times</li>
        <li aria-hidden="true">NBC</li><li aria-hidden="true">BBC</li><li aria-hidden="true">Forbes</li><li aria-hidden="true">Newsweek</li><li aria-hidden="true">Politico</li><li aria-hidden="true">Inc.</li><li aria-hidden="true">Esquire</li><li aria-hidden="true">LA Times</li>
      </ul>
    </div>
  </section>

  <!-- ================= ABOUT (short) ================= -->
  <section class="section about" id="about">
    <div class="container about-grid">
      <div class="about-copy reveal">
        <p class="eyebrow"><span class="eyebrow-line"></span>About Us</p>
        <h2>Your Trusted Partner for Quality Book Publishing in the US</h2>
        <p>Infinite Books Publishing helps new and experienced authors turn ideas into professionally published books. With complete services — editing, formatting, cover design and distribution on major platforms — we make publishing simple, accessible and high-quality for every author.</p>
        <ul class="check-list two-col">
          <li>Book Cover Design</li>
          <li>Audiobook Editing</li>
          <li>Ghostwriting</li>
          <li>Book Marketing</li>
        </ul>
        <a href="{{ route('about') }}" class="btn btn-outline" style="margin-top:1.75rem;">More About Us
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
        </a>
      </div>
      <div class="stats-grid reveal">
        <div class="stat-card"><span class="stat-num">10+</span><span class="stat-label">Years of Experience</span></div>
        <div class="stat-card"><span class="stat-num">700+</span><span class="stat-label">Books Published</span></div>
        <div class="stat-card"><span class="stat-num">250+</span><span class="stat-label">Skilled Writers</span></div>
        <div class="stat-card"><span class="stat-num">100%</span><span class="stat-label">Rights Retained</span></div>
      </div>
    </div>
  </section>

  <!-- ================= SERVICES ================= -->
  <section class="section section-alt" id="services">
    <div class="container">
      <div class="section-head reveal">
        <p class="eyebrow center"><span class="eyebrow-line"></span>What We Do</p>
        <h2>Elevate Your Work with Our <span class="accent">Publishing Suite</span></h2>
        <p class="section-lede">End-to-end support at every stage — refine, develop and publish your work with confidence.</p>
      </div>
      <div class="scroller">
        <div class="card-grid services-grid">
          <a class="feature-card reveal" href="{{ route('services.show', 'ghostwriting') }}">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path></svg>
            </span>
            <h3>Book Writing &amp; Ghostwriting</h3>
            <p>Have a story but need the words? Our writers shape your ideas into a polished manuscript that sounds like you.</p>
          </a>
          <a class="feature-card reveal" href="{{ route('services.show', 'editing-proofreading') }}">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 5H3"></path><path d="M17 12H3"></path><path d="M21 19H3"></path><path d="m17 8 4 4-4 4"></path></svg>
            </span>
            <h3>Editing &amp; Proofreading</h3>
            <p>Line editing, structural feedback and meticulous proofreading so your book is error-free and reader-ready.</p>
          </a>
          <a class="feature-card reveal" href="{{ route('services.show', 'cover-design') }}">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5"></circle><circle cx="17.5" cy="10.5" r=".5"></circle><circle cx="8.5" cy="7.5" r=".5"></circle><circle cx="6.5" cy="12.5" r=".5"></circle><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path></svg>
            </span>
            <h3>Cover Design &amp; Illustration</h3>
            <p>Covers and interior artwork that capture your story at a glance — from children's books to literary fiction.</p>
          </a>
          <a class="feature-card reveal" href="{{ route('services.show', 'publishing-distribution') }}">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
            </span>
            <h3>Publishing &amp; Distribution</h3>
            <p>Formatting and release on Amazon, IngramSpark and other major platforms — print, e-book and beyond.</p>
          </a>
          <a class="feature-card reveal" href="{{ route('services.show', 'book-marketing') }}">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>
            </span>
            <h3>Book Marketing</h3>
            <p>Bespoke launch campaigns, social promotion and PR support that put your book in front of the right readers.</p>
          </a>
          <a class="feature-card reveal" href="{{ route('services.show', 'audiobook-production') }}">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 10v4"></path><path d="M6 6v12"></path><path d="M10 3v18"></path><path d="M14 8v9"></path><path d="M18 5v14"></path><path d="M22 10v4"></path></svg>
            </span>
            <h3>Audiobook Production</h3>
            <p>Professional narration, editing and mastering that brings your book to listeners everywhere.</p>
          </a>
        </div>
      </div>
      <p class="swipe-hint">Swipe to explore →</p>
      <div style="text-align:center;margin-top:2.5rem;">
        <a href="{{ route('services.index') }}" class="btn btn-outline">View All Services</a>
      </div>
    </div>
  </section>

  <!-- ================= PORTFOLIO (preview) ================= -->
  <section class="section" id="portfolio">
    <div class="container">
      <div class="section-head reveal">
        <p class="eyebrow center"><span class="eyebrow-line"></span>Our Shelf</p>
        <h2>Discover Our Portfolio</h2>
        <p class="section-lede">Fiction, non-fiction, children's books, horror, memoirs and audiobooks — every genre gets the Infinite treatment.</p>
      </div>
      <div class="scroller">
        <div class="card-grid portfolio-grid">
          <figure class="portfolio-card reveal">
            <img src="{{ asset('assets/images/book-fiction.jpg') }}" alt="Fiction book cover — Literary &amp; Contemporary" loading="lazy">
            <figcaption><span class="tag">Fiction</span><span class="sub">Literary &amp; Contemporary</span></figcaption>
          </figure>
          <figure class="portfolio-card reveal">
            <img src="{{ asset('assets/images/book-children.jpg') }}" alt="Children's Books book cover — Illustrated Picture Books" loading="lazy">
            <figcaption><span class="tag">Children's Books</span><span class="sub">Illustrated Picture Books</span></figcaption>
          </figure>
          <figure class="portfolio-card reveal">
            <img src="{{ asset('assets/images/book-memoir.jpg') }}" alt="Memoir book cover — Biography &amp; Life Stories" loading="lazy">
            <figcaption><span class="tag">Memoir</span><span class="sub">Biography &amp; Life Stories</span></figcaption>
          </figure>
        </div>
      </div>
      <p class="swipe-hint">Swipe to explore →</p>
      <div style="text-align:center;margin-top:2.5rem;">
        <a href="{{ route('portfolio') }}" class="btn btn-outline">View Full Portfolio</a>
      </div>
    </div>
  </section>

  <!-- ================= PROCESS ================= -->
  <section class="section section-alt" id="process">
    <div class="container">
      <div class="section-head reveal">
        <p class="eyebrow center"><span class="eyebrow-line"></span>How It Works</p>
        <h2>Understanding the <span class="accent">Publishing Process</span></h2>
      </div>
      <div class="scroller">
        <div class="card-grid process-grid">
          <article class="process-card reveal">
            <span class="process-num">01</span>
            <h3>Share Your Vision</h3>
            <p>Tell us about your book — an outline, a draft or just an idea. We map out a plan that fits your goals.</p>
          </article>
          <article class="process-card reveal">
            <span class="process-num">02</span>
            <h3>Write &amp; Refine</h3>
            <p>Writing support, editing and proofreading bring your manuscript to a professional standard.</p>
          </article>
          <article class="process-card reveal">
            <span class="process-num">03</span>
            <h3>Design &amp; Produce</h3>
            <p>Cover design, interior formatting and illustration give your book a look readers can't resist.</p>
          </article>
          <article class="process-card reveal">
            <span class="process-num">04</span>
            <h3>Publish &amp; Promote</h3>
            <p>We distribute on major platforms and build a marketing campaign so your story finds its audience.</p>
          </article>
        </div>
      </div>
      <p class="swipe-hint">Swipe to explore →</p>
    </div>
  </section>

  <!-- ================= WHY US ================= -->
  <section class="section" id="why">
    <div class="container">
      <div class="section-head reveal">
        <p class="eyebrow center"><span class="eyebrow-line"></span>Why Infinite Books</p>
        <h2>Built on Craft, Not Shortcuts</h2>
        <p class="section-lede">Authors come to us for the writing. They stay for the way the whole process is run.</p>
      </div>
      <div class="scroller">
        <div class="card-grid why-grid">
          <article class="feature-card reveal">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"></path><circle cx="12" cy="8" r="6"></circle></svg>
            </span>
            <h3>Award-Calibre Craft</h3>
            <p>Editors and ghostwriters drawn from trade publishing — every manuscript is treated like a lead title.</p>
          </article>
          <article class="feature-card reveal">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </span>
            <h3>Deadlines We Keep</h3>
            <p>A published schedule with milestone check-ins, so you always know what ships and when.</p>
          </article>
          <article class="feature-card reveal">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </span>
            <h3>100% Rights Retained</h3>
            <p>You own your manuscript, your royalties and your ISBN. No hidden claims, ever.</p>
          </article>
          <article class="feature-card reveal">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </span>
            <h3>One Dedicated Team</h3>
            <p>A single project manager plus your writer, designer and marketer — no revolving door.</p>
          </article>
        </div>
      </div>
      <p class="swipe-hint">Swipe to explore →</p>
    </div>
  </section>

  <!-- ================= REVIEWS (preview) ================= -->
  <section class="section section-alt" id="reviews">
    <div class="container">
      <div class="section-head reveal">
        <p class="eyebrow center"><span class="eyebrow-line"></span>Happy Authors</p>
        <h2>What Our Clients Say</h2>
      </div>
      <div class="scroller">
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
        </div>
      </div>
      <p class="swipe-hint">Swipe to explore →</p>
      <div style="text-align:center;margin-top:2.5rem;">
        <a href="{{ route('testimonials') }}" class="btn btn-outline">Read All Reviews</a>
      </div>
    </div>
  </section>

  <!-- ================= FAQ ================= -->
  <section class="section" id="faq">
    <div class="container container-narrow">
      <div class="section-head reveal">
        <p class="eyebrow center"><span class="eyebrow-line"></span>FAQ</p>
        <h2>Questions, <span class="accent">Answered</span></h2>
      </div>
      <div class="accordion reveal">
        <div class="accordion-item is-open">
          <button class="accordion-trigger" aria-expanded="true">
            <span>What is Infinite Books Publishing?</span>
            <span class="accordion-icon">+</span>
          </button>
          <div class="accordion-panel">
            <p>We're a full-service book publishing company that helps authors turn ideas into professionally published books — from the first draft to the final release.</p>
          </div>
        </div>
        <div class="accordion-item">
          <button class="accordion-trigger" aria-expanded="false">
            <span>Which services do you offer?</span>
            <span class="accordion-icon">+</span>
          </button>
          <div class="accordion-panel">
            <p>Writing and ghostwriting, editing, formatting, cover design, illustration, audiobook production, distribution and book marketing.</p>
          </div>
        </div>
        <div class="accordion-item">
          <button class="accordion-trigger" aria-expanded="false">
            <span>Do I keep the rights to my book?</span>
            <span class="accordion-icon">+</span>
          </button>
          <div class="accordion-panel">
            <p>Yes. You retain full ownership and rights to your work throughout the entire publishing process.</p>
          </div>
        </div>
        <div class="accordion-item">
          <button class="accordion-trigger" aria-expanded="false">
            <span>I'm a first-time author. Can you guide me?</span>
            <span class="accordion-icon">+</span>
          </button>
          <div class="accordion-panel">
            <p>Absolutely. Most of our authors are publishing for the first time — we walk you through every step, from manuscript to marketplace.</p>
          </div>
        </div>
        <div class="accordion-item">
          <button class="accordion-trigger" aria-expanded="false">
            <span>Can you publish my book on Amazon?</span>
            <span class="accordion-icon">+</span>
          </button>
          <div class="accordion-panel">
            <p>Yes. We handle formatting and distribution on major platforms including Amazon and IngramSpark, in print and e-book formats.</p>
          </div>
        </div>
        <div class="accordion-item">
          <button class="accordion-trigger" aria-expanded="false">
            <span>How do I get started?</span>
            <span class="accordion-icon">+</span>
          </button>
          <div class="accordion-panel">
            <p>Reach out through the contact form or call us — we'll discuss your project and outline a publishing plan together.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= FINAL CONTACT ================= -->
  <section class="section contact-section" id="contact-cta">
    <div class="container contact-grid">
      <div class="contact-copy reveal">
        <p class="eyebrow"><span class="eyebrow-line"></span>Get In Touch</p>
        <h2>Write. Publish. <span class="accent">Succeed.</span></h2>
        <p>Got a story that's itching to hop onto pages? Sign up now to hear about our exclusive discounts — or just say hello. We're all ears.</p>
        <ul class="check-list">
          <li>End-to-end professional book publishing</li>
          <li>Dedicated author success support team</li>
          <li>Global distribution across major platforms</li>
          <li>Premium editing, design and formatting</li>
          <li>You keep 100% ownership of your book</li>
        </ul>
        <div class="contact-methods">
          <a class="contact-method" href="tel:9802234655">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.804 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
            </span>
            <span><strong>Give us a ring</strong><span>+1 (980) 223-4655</span></span>
          </a>
          <a class="contact-method" href="mailto:info@infinitebookspublishing.com">
            <span class="feature-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
            </span>
            <span><strong>Drop us a line</strong><span>info@infinitebookspublishing.com</span></span>
          </a>
        </div>
      </div>
      <div class="hero-form-card reveal">
        <h2>Start Your Publishing Journey</h2>
        <p>Fill out the form and our team will reach out to you.</p>
        @if (session('success'))
          <p class="form-success">{{ session('success') }}</p>
        @endif
        <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
          @csrf
          <label class="sr-only" for="cta-name">Full Name</label>
          <input id="cta-name" name="name" type="text" placeholder="Full Name" value="{{ old('name') }}" required>
          <label class="sr-only" for="cta-phone">Phone Number</label>
          <input id="cta-phone" name="phone" type="tel" placeholder="Phone Number" value="{{ old('phone') }}" required>
          <label class="sr-only" for="cta-email">Email Address</label>
          <input id="cta-email" name="email" type="email" placeholder="Email Address" value="{{ old('email') }}" required>
          <label class="sr-only" for="cta-message">About Your Project</label>
          <textarea id="cta-message" name="message" placeholder="About Your Project" rows="4" required>{{ old('message') }}</textarea>
          <button type="submit" class="btn btn-primary btn-block">Send Email</button>
        </form>
      </div>
    </div>
  </section>

@endsection
