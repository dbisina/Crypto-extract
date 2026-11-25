@php
    $items = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard', 'M3 12 12 3l9 9M5 10v10h5v-6h4v6h5V10'],
        ['Articles', 'admin.articles.index', 'admin.articles.*', 'M5 4h14v16H5zM8 8h8M8 12h8M8 16h5'],
        ['Categories', 'admin.categories.index', 'admin.categories.*', 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z'],
        ['Authors', 'admin.authors.index', 'admin.authors.*', 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM4 21c0-4 4-6 8-6s8 2 8 6'],
        ['Advertising', 'admin.ads.index', 'admin.ads.*', 'M3 11v2l12 5V6L3 11ZM18 9a4 4 0 0 1 0 6'],
        ['Media', 'admin.media', 'admin.media*', 'M4 5h16v14H4zM4 16l5-5 4 4 3-3 4 4M9 9.500h.01'],
        ['Inbox', 'admin.inbox', 'admin.inbox', 'M3 7l9 6 9-6M3 7v10h18V7M3 7l3-3h12l3 3'],
    ];
@endphp
<!DOCTYPE html>
<html lang="en" class="bg-ink">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"><meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') · KryptoExtract CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body x-data="{ nav: false }" class="min-h-screen bg-ink text-paper">
<div class="lg:grid lg:grid-cols-[240px_1fr]">
    {{-- Sidebar --}}
    <aside class="border-b border-line bg-ink-2 lg:min-h-screen lg:border-b-0 lg:border-r">
        <div class="flex items-center justify-between px-5 py-4 lg:block lg:py-7">
            <a href="{{ route('admin.dashboard') }}" class="font-display text-[22px] font-extrabold leading-none">Krypto<span class="text-gold">Extract</span><span class="mt-1 block font-mono text-[9px] font-normal uppercase tracking-[0.3em] text-mute">Newsroom CMS</span></a>
            <button @click="nav = !nav" class="p-2 lg:hidden" aria-label="Toggle navigation"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 7h18M3 12h18M3 17h18"/></svg></button>
        </div>
        <nav :class="nav ? 'block' : 'hidden lg:block'" class="px-3 pb-6" aria-label="Admin">
            @foreach ($items as [$label, $route, $match, $icon])
                <a href="{{ route($route) }}" @class(['flex items-center gap-3 border-l-2 px-3 py-2.5 text-[14px] transition-colors', 'border-gold bg-card text-gold' => request()->routeIs($match), 'border-transparent text-fog hover:text-paper' => ! request()->routeIs($match)])>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icon }}"/></svg>{{ $label }}
                </a>
            @endforeach
            <div class="mx-3 mt-6 border-t border-line pt-4">
                <a href="{{ route('home') }}" target="_blank" class="block py-2 text-[13px] text-fog hover:text-gold">View site ↗</a>
                <form method="post" action="{{ route('logout') }}">@csrf<button class="py-2 text-[13px] text-fog hover:text-gold">Sign out ({{ auth()->user()->name }})</button></form>
            </div>
        </nav>
    </aside>

    <div class="min-w-0">
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-5 py-5 md:px-10">
            <div><p class="kicker">@yield('eyebrow', 'Admin')</p><h1 class="font-display text-[28px] font-bold leading-tight md:text-[34px]">@yield('heading')</h1></div>
            <div class="flex gap-2">@yield('actions')</div>
        </header>
        <main class="px-5 py-8 md:px-10">
            @if (session('status'))<div class="mb-6 border border-up/50 bg-up/10 px-4 py-3 text-[14px] text-up" role="status">{{ session('status') }}</div>@endif
            @if ($errors->any())
                <div class="mb-6 border border-down/50 bg-down/10 px-4 py-3 text-[14px] text-down" role="alert"><p class="font-medium">Please fix the following:</p><ul class="mt-1 list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
