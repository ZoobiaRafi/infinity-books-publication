<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Infinite Books Publishing — Turn Your Ideas into Bestselling Books')</title>
<meta name="description" content="@yield('description', 'From first draft to bookstore shelf — writing, editing, design, publishing and marketing under one roof. A trusted US publishing company.')">
<link rel="icon" href="{{ asset('assets/favicon-32.png') }}" type="image/png" sizes="32x32">
<link rel="icon" href="{{ asset('assets/favicon-16.png') }}" type="image/png" sizes="16x16">
<link rel="apple-touch-icon" href="{{ asset('assets/apple-touch-icon.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@stack('head')
</head>
<body>

<a class="skip-link" href="#main">Skip to content</a>

@include('partials.header')

<main id="main">
@yield('content')
</main>

@include('partials.footer')

<div class="mobile-sticky-bar">
  <a href="tel:9802234655" class="btn btn-outline">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.804 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
    Call Now
  </a>
  <a href="{{ route('contact.index') }}" class="btn btn-primary">Free Consultation</a>
</div>

<script src="{{ asset('js/script.js') }}"></script>
@stack('scripts')

{{-- Tawk.to live chat widget --}}
<script type="text/javascript">
var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
(function () {
  var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0];
  s1.async = true;
  s1.src = 'https://embed.tawk.to/TAWK_PROPERTY_ID/TAWK_WIDGET_ID';
  s1.charset = 'UTF-8';
  s1.setAttribute('crossorigin', '*');
  s0.parentNode.insertBefore(s1, s0);
})();
</script>
</body>
</html>
