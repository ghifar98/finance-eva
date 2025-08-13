<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="theme-color" content="#0f172a" />

<title>{{ $title ?? config('app.name', 'Luxury Brand') }} | Premium Experience</title>

<!-- Favicon Package -->
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">

<!-- Premium Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">

<!-- CSS/JS Assets - Corrected Vite implementation -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Open Graph / Social Meta (Premium Branding) -->
<meta property="og:title" content="{{ $title ?? config('app.name', 'Luxury Brand') }}">
<meta property="og:description" content="Experience our premium service with elegant design and exceptional performance">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('images/og-preview.jpg') }}">

<!-- WireUI Scripts -->
<wireui:scripts />

<!-- Alpine.js with Premium Extensions -->
<script src="//unpkg.com/alpinejs@3.13.0/dist/cdn.min.js" defer></script>
<script src="//unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js" defer></script>

@stack('scripts')
@fluxAppearance