@php
    $siteName = 'KryptoExtract';
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' | '.$siteName : $siteName.': African Crypto & Financial News';
    $desc = trim($__env->yieldContent('description')) ?: 'KryptoExtract is an independent publication covering cryptocurrency, blockchain, stablecoins and digital finance, with Africa at the centre.';
    $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
    $ogImage = trim($__env->yieldContent('og_image')) ?: 'https://images.unsplash.com/photo-1765475467677-579353b25ce0?auto=format&fit=crop&q=75&w=1200&h=630';
    $ogType = trim($__env->yieldContent('og_type')) ?: 'website';
    $nav = [
        ['Home', route('home'), 'home'], ['Latest', route('latest'), 'latest'], ['Africa', route('africa'), 'africa*'],
        ['Markets', route('markets'), 'markets'], ['Regulation', route('regulation'), 'regulation'],
        ['Stablecoins', route('stablecoins'), 'stablecoins'], ['DeFi', route('defi'), 'defi'],
        ['Insights', route('insights'), 'insights'], ['Learn', route('learn'), 'learn'],
    ];
@endphp
<!DOCTYPE html>
<html lang="en" class="bg-ink">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $desc }}">
    <link rel="canonical" href="{{ $canonical }}">
    @hasSection('noindex')<meta name="robots" content="noindex, follow">@else<meta name="robots" content="index, follow, max-image-preview:large">@endif
    <meta name="theme-color" content="#090A0B">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $pageTitle ?: $siteName }}">
    <meta property="og:description" content="{{ $desc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle ?: $siteName }}">
    <meta name="twitter:description" content="{{ $desc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    @yield('meta')
    <link rel="alternate" type="application/rss+xml" title="{{ $siteName }}" href="{{ route('rss') }}">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%23090A0B'/%3E%3Ctext x='16' y='24' font-family='Georgia,serif' font-size='24' font-weight='700' fill='%23C9A44C' text-anchor='middle'%3EK%3C/text%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,500;0,700;0,800;1,500;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org', '@type' => 'NewsMediaOrganization', 'name' => $siteName, 'url' => url('/'),
        'description' => 'Independent financial journalism on cryptocurrency, blockchain and digital assets, with Africa at the centre.',
        'sameAs' => ['https://x.com/kryptoextract', 'https://t.me/kryptoextract'],
        'publishingPrinciples' => route('editorial'), 'correctionsPolicy' => route('corrections'),
    ], JSON_UNESCAPED_SLASHES) !!}</script>
    @stack('jsonld')
</head>
<body x-data="{ menu: false, search: false }" :class="(menu || search) && 'overflow-hidden'" @keydown.escape.window="menu = false; search = false">
@yield('progress')
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-3 focus:top-3 focus:z-[100] focus:bg-gold focus:px-4 focus:py-2 focus:text-ink">Skip to content</a>

{{-- Masthead --}}
<header class="relative z-40 bg-ink">
    <div class="hidden border-b border-line md:block">
        <div class="wrap flex h-9 items-center justify-between">
            <span class="meta">{{ now()->format('l, j F Y') }} · Lagos · Accra · Nairobi · Johannesburg</span>
            <div class="flex items-center gap-5">
                <a href="{{ route('tip') }}" class="meta transition-colors hover:text-gold">Submit a tip</a>
                <a href="{{ route('advertise') }}" class="meta transition-colors hover:text-gold">Advertise</a>
                <a href="https://x.com/kryptoextract" rel="noopener" class="meta transition-colors hover:text-gold">X</a>
                <a href="https://t.me/kryptoextract" rel="noopener" class="meta transition-colors hover:text-gold">Telegram</a>
                <a href="{{ route('rss') }}" class="meta transition-colors hover:text-gold">RSS</a>
            </div>
        </div>
    </div>

    <div class="wrap grid grid-cols-[1fr_auto_1fr] items-center py-4 md:py-6">
        <div class="flex items-center">
            <button @click="menu = true" class="-ml-2 p-2 md:hidden" aria-label="Open menu">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 7h18M3 12h18M3 17h12"/></svg>
            </button>
            <button @click="search = true; $nextTick(() => $refs.q.focus())" class="hidden items-center gap-2 text-fog transition-colors hover:text-gold md:flex" aria-label="Search">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <span class="meta !text-inherit">Search</span>
            </button>
        </div>

        <a href="{{ route('home') }}" class="text-center" aria-label="KryptoExtract home">
            <span class="font-display text-[28px] font-extrabold leading-none tracking-tight sm:text-[34px] md:text-[48px]">Krypto<span class="text-gold">Extract</span></span>
            <span class="mt-1 hidden font-mono text-[9px] uppercase tracking-[0.34em] text-mute md:block">African digital finance, independently reported</span>
        </a>

        <div class="flex items-center justify-end gap-3">
            <button @click="search = true; $nextTick(() => $refs.q.focus())" class="p-2 text-fog md:hidden" aria-label="Search">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </button>
            <a href="#newsletter" class="btn-gold hidden !px-4 !py-2.5 md:inline-flex">The Daily Extract</a>
        </div>
    </div>

    {{-- Primary nav --}}
    <nav class="border-y border-line" aria-label="Primary">
        <div class="wrap">
            <ul class="no-scrollbar -mx-5 flex overflow-x-auto px-5 md:mx-0 md:justify-center md:overflow-visible md:px-0">
                @foreach ($nav as [$label, $href, $match])
                    <li class="shrink-0">
                        <a href="{{ $href }}" @class([
                            'relative block px-3.5 py-3.5 text-[12px] font-semibold uppercase tracking-[0.12em] transition-colors lg:px-5',
                            'text-gold' => request()->routeIs($match),
                            'text-fog hover:text-paper' => ! request()->routeIs($match),
                        ])>
                            {{ $label }}
                            <span @class(['absolute inset-x-3.5 bottom-0 h-[2px] bg-gold transition-transform duration-300 origin-left lg:inset-x-5', 'scale-x-100' => request()->routeIs($match), 'scale-x-0' => ! request()->routeIs($match)])></span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</header>

{{-- Market ticker --}}
@include('partials.ticker', ['snapshot' => $tickerSnapshot ?? null])

{{-- Mobile menu --}}
<div x-cloak x-show="menu" class="fixed inset-0 z-50 md:hidden" role="dialog" aria-modal="true" aria-label="Menu">
    <div x-show="menu" x-transition.opacity class="absolute inset-0 bg-black/70" @click="menu = false"></div>
    <div x-show="menu" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
         class="absolute inset-y-0 left-0 flex w-[86%] max-w-sm flex-col overflow-y-auto border-r border-line bg-ink-2 p-6">
        <div class="flex items-center justify-between">
            <span class="font-display text-2xl font-extrabold">Krypto<span class="text-gold">Extract</span></span>
            <button @click="menu = false" class="-mr-2 p-2" aria-label="Close menu"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>
        <ul class="mt-8 divide-y divide-line border-y border-line">
            @foreach ($nav as [$label, $href, $match])
                <li><a href="{{ $href }}" class="flex items-baseline justify-between py-4 font-display text-[26px] font-bold {{ request()->routeIs($match) ? 'text-gold' : 'text-paper' }}">{{ $label }}<span class="font-mono text-xs text-mute">→</span></a></li>
            @endforeach
        </ul>
        <div class="mt-8 grid grid-cols-2 gap-3 text-sm text-fog">
            <a href="{{ route('tip') }}" class="hover:text-gold">Submit a tip</a><a href="{{ route('advertise') }}" class="hover:text-gold">Advertise</a>
            <a href="{{ route('about') }}" class="hover:text-gold">About</a><a href="{{ route('contact') }}" class="hover:text-gold">Contact</a>
        </div>
        <a href="#newsletter" @click="menu = false" class="btn-gold mt-8">The Daily Extract</a>
        <div class="mt-auto flex gap-5 pt-8"><a href="https://x.com/kryptoextract" class="meta hover:text-gold">X</a><a href="https://t.me/kryptoextract" class="meta hover:text-gold">Telegram</a><a href="{{ route('rss') }}" class="meta hover:text-gold">RSS</a></div>
    </div>
</div>

{{-- Search overlay --}}
<div x-cloak x-show="search" x-transition.opacity.duration.200ms class="fixed inset-0 z-50 bg-ink/95 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Search">
    <div class="wrap pt-6 md:pt-16">
        <div class="flex justify-end"><button @click="search = false" class="meta p-2 hover:text-gold">Close ✕</button></div>
        <form action="{{ route('search') }}" method="get" class="mx-auto mt-10 max-w-3xl">
            <label for="q" class="kicker">Search KryptoExtract</label>
            <div class="mt-3 flex items-end border-b-2 border-gold">
                <input x-ref="q" id="q" name="q" type="search" autocomplete="off" placeholder="Stablecoins, Nigeria, ETF…" class="w-full bg-transparent py-3 font-display text-3xl text-paper placeholder:text-mute focus:outline-none md:text-5xl">
                <button class="pb-4 pl-4 text-gold" aria-label="Search"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></button>
            </div>
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach (['Nigeria', 'Stablecoins', 'Bitcoin ETF', 'Kenya', 'Regulation', 'DeFi'] as $t)
                    <a href="{{ route('search', ['q' => $t]) }}" class="border border-line-2 px-3 py-1.5 font-mono text-[11px] uppercase tracking-wider text-fog transition-colors hover:border-gold hover:text-gold">{{ $t }}</a>
                @endforeach
            </div>
        </form>
    </div>
</div>

@if (! request()->routeIs('home'))
    <div class="wrap pt-6"><x-ad placement="header_leaderboard" class="mx-auto max-w-[728px]" /></div>
@endif

<main id="main">
    @yield('content')
</main>

@include('partials.footer')
</body>
</html>
