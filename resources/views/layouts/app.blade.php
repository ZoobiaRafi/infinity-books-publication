<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Infinite Books Publishing — Turn Your Ideas into Bestselling Books')</title>
<meta name="description" content="@yield('description', 'From first draft to bookstore shelf — writing, editing, design, publishing and marketing under one roof. A trusted US publishing company.')">
@php
  // Anchored to config('app.url') rather than the live request's host/scheme,
  // so this stays the same fixed non-www https URL regardless of how a page
  // was actually reached (e.g. www, which redirects before Laravel ever sees
  // the request, but this avoids depending on that holding true forever).
  // No trailing slash on the root path specifically, matching what
  // route('home') (used by every internal link and the sitemap) produces -
  // a mismatched trailing slash here would just create a second,
  // self-inflicted version of the exact duplicate-URL problem this exists
  // to fix.
  $requestPath = request()->path();
  $canonicalUrl = $requestPath === '/'
    ? rtrim(config('app.url'), '/')
    : rtrim(config('app.url'), '/').'/'.$requestPath;

  // Built as a PHP array and JSON-encoded below rather than hand-written -
  // "@context"/"@type" as literal text would otherwise sit in a Blade
  // template looking exactly like (unregistered, so harmless, but fragile)
  // Blade directives.
  $organizationSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Infinite Books Publishing',
    'url' => config('app.url'),
    'logo' => asset('assets/favicon-48.png'),
    'telephone' => '+1-980-223-4655',
    'email' => 'info@infinitebookspublishing.com',
  ];
@endphp
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta property="og:site_name" content="Infinite Books Publishing">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:title" content="@yield('title', 'Infinite Books Publishing — Turn Your Ideas into Bestselling Books')">
<meta property="og:description" content="@yield('description', 'From first draft to bookstore shelf — writing, editing, design, publishing and marketing under one roof. A trusted US publishing company.')">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="@yield('og_image', asset('assets/images/hero-desk.jpg'))">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'Infinite Books Publishing — Turn Your Ideas into Bestselling Books')">
<meta name="twitter:description" content="@yield('description', 'From first draft to bookstore shelf — writing, editing, design, publishing and marketing under one roof. A trusted US publishing company.')">
<meta name="twitter:image" content="@yield('og_image', asset('assets/images/hero-desk.jpg'))">
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@stack('structured-data')
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
{{-- Moved back to bottom-right per request. Note: the contact form's
     submit button sits in the page's right-hand column on every page
     that has one (home hero, home CTA, /contact), and this exact
     position previously swallowed clicks on it on short viewports —
     see git history on this file if that resurfaces. --}}
Tawk_API.customStyle = {
  visibility: {
    desktop: { position: 'br', xOffset: 20, yOffset: 20 },
    mobile: { position: 'br', xOffset: 10, yOffset: 10 }
  }
};
(function () {
  var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0];
  s1.async = true;
  s1.src = 'https://embed.tawk.to/6a9db178b08ce23448acbea4/1k1rvqgdm';
  s1.charset = 'UTF-8';
  s1.setAttribute('crossorigin', '*');
  s0.parentNode.insertBefore(s1, s0);
})();
</script>
</body>
</html>
