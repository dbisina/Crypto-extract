@props(['eyebrow' => null, 'title', 'intro' => null, 'crumbs' => []])
<header class="wrap pt-8 md:pt-12">
    <nav aria-label="Breadcrumb" class="meta flex flex-wrap items-center gap-2">
        <a href="{{ route('home') }}" class="transition-colors hover:text-gold">Home</a>
        @foreach ($crumbs as $c)
            <span aria-hidden="true">/</span>
            @if (! empty($c[1]))<a href="{{ $c[1] }}" class="transition-colors hover:text-gold">{{ $c[0] }}</a>@else<span class="text-fog" aria-current="page">{{ $c[0] }}</span>@endif
        @endforeach
    </nav>
    <div class="mt-8 grid gap-6 border-b border-line pb-10 md:grid-cols-12 md:items-end">
        <div class="md:col-span-8">
            @if ($eyebrow)<p class="kicker">{{ $eyebrow }}</p>@endif
            <h1 class="hed mt-2 text-[44px] sm:text-[60px] lg:text-[88px]">{{ $title }}</h1>
        </div>
        @if ($intro)<p class="max-w-md text-[17px] leading-relaxed text-fog md:col-span-4 md:justify-self-end">{{ $intro }}</p>@endif
    </div>
</header>
