<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Infinite Books Publishing — Turn Your Ideas into Bestselling Books')</title>
<meta name="description" content="@yield('description', 'From first draft to bookstore shelf — writing, editing, design, publishing and marketing under one roof. A trusted US publishing company.')">
<link rel="icon" href="{{ asset('assets/favicon.svg') }}" type="image/svg+xml">
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

@include('partials.chat-widget')

<div class="mobile-sticky-bar">
  <a href="tel:9802234655" class="btn btn-outline">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.804 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
    Call Now
  </a>
  <a href="{{ route('contact.index') }}" class="btn btn-primary">Free Consultation</a>
</div>

<script src="{{ asset('js/script.js') }}"></script>
<script>
  window.chatConfig = {
    sendUrl: @json(route('chat.send')),
    pollUrl: @json(route('chat.poll')),
    csrfToken: @json(csrf_token())
  };
</script>
<script src="{{ asset('js/chat-widget.js') }}"></script>
@stack('scripts')
</body>
</html>
