<footer class="site-footer">
  <div class="container footer-columns">
    <div class="footer-col footer-brand">
      <a href="{{ route('home') }}" class="logo">
        <img src="{{ asset('assets/favicon.png') }}" alt="" class="logo-icon-img">
      <span class="logo-text">Infinite Books <span class="accent">Publishing</span></span>
      </a>
      <p>From first draft to bookstore shelf — writing, editing, design, publishing and marketing under one roof.</p>
    </div>
    <div class="footer-col">
      <h4>Company</h4>
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('portfolio') }}">Portfolio</a>
      <a href="{{ route('testimonials') }}">Testimonials</a>
      <a href="{{ route('blog.index') }}">Blog</a>
      <a href="{{ route('contact.index') }}">Contact</a>
    </div>
    <div class="footer-col">
      <h4>Services</h4>
      <a href="{{ route('services.show', 'ghostwriting') }}">Ghostwriting</a>
      <a href="{{ route('services.show', 'editing-proofreading') }}">Editing &amp; Proofreading</a>
      <a href="{{ route('services.show', 'cover-design') }}">Cover Design</a>
      <a href="{{ route('services.show', 'publishing-distribution') }}">Publishing &amp; Distribution</a>
      <a href="{{ route('services.show', 'book-marketing') }}">Book Marketing</a>
      <a href="{{ route('services.show', 'audiobook-production') }}">Audiobook Production</a>
    </div>
    <div class="footer-col">
      <h4>Legal</h4>
      <a href="{{ route('terms') }}">Terms &amp; Conditions</a>
      <a href="{{ route('privacy') }}">Privacy Policy</a>
    </div>
  </div>
  <div class="container footer-bottom">
    <p>&copy; {{ date('Y') }} Infinite Books Publishing</p>
    <p>+1 (980) 223-4655 &middot; info@infinitebookspublishing.com</p>
  </div>
</footer>
