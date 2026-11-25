@extends('layouts.app')

@section('title', 'African Crypto, Blockchain & Digital Finance News')
@section('description', 'Independent financial journalism on cryptocurrency, blockchain, stablecoins and regulation, with Africa at the centre. Reported from Lagos, Accra, Nairobi and Johannesburg.')

@section('content')
{{-- ═══ Editorial hero ═══ --}}
<section class="wrap pb-12 pt-8 md:pb-16 md:pt-10">
    <div class="grid gap-10 lg:grid-cols-12 lg:gap-12">
        <div class="lg:col-span-8">
            @if ($featured) @include('partials.story', ['article' => $featured, 'variant' => 'lead']) @endif
        </div>

        <aside class="lg:col-span-4 lg:border-l lg:border-line lg:pl-10">
            <div class="flex items-baseline justify-between border-t-2 border-gold pt-3">
                <h2 class="kicker">Top stories</h2>
                <a href="{{ route('latest') }}" class="meta transition-colors hover:text-gold">All →</a>
            </div>
            <div class="divide-y divide-line">
                @foreach ($secondary as $s)
                    <div class="py-5 first:pt-5">
                        @include('partials.story', ['article' => $s, 'variant' => 'row', 'noExcerpt' => true, 'compact' => true])
                    </div>
                @endforeach
            </div>
        </aside>
    </div>
</section>

<div class="wrap pb-12"><x-ad placement="homepage_banner" class="mx-auto max-w-[970px]" /></div>

{{-- ═══ Latest + sidebar ═══ --}}
<section class="wrap pb-16 md:pb-24">
    <div class="grid gap-12 lg:grid-cols-12 lg:gap-12">
        <div class="lg:col-span-8">
            <header class="flex items-end justify-between border-b border-line pb-4">
                <div><p class="kicker">Newsroom</p><h2 class="hed mt-1 text-[32px] md:text-[40px]">Latest news</h2></div>
                <a href="{{ route('latest') }}" class="btn-line !px-4 !py-2.5">Full archive →</a>
            </header>
            <div class="divide-y divide-line">
                @foreach ($latest as $a)
                    <div class="py-7">@include('partials.story', ['article' => $a, 'variant' => 'row'])</div>
                @endforeach
            </div>
        </div>

        <aside class="space-y-10 lg:col-span-4">
            <div class="lg:sticky lg:top-6 space-y-10">
                <x-ad placement="homepage_sidebar" class="mx-auto w-full max-w-[300px]" />
                <section>
                    <h3 class="kicker border-t-2 border-gold pt-3">Most read</h3>
                    <ol class="mt-5 space-y-6">
                        @foreach ($secondary->concat($latest)->take(5) as $i => $m)
                            <li>@include('partials.story', ['article' => $m, 'variant' => 'text', 'n' => $i + 1])</li>
                        @endforeach
                    </ol>
                </section>
            </div>
        </aside>
    </div>
</section>

{{-- ═══ Africa in focus ═══ --}}
<section class="relative overflow-hidden border-y border-line bg-ink-2 py-16 md:py-24" aria-labelledby="africa-h">
    <div aria-hidden="true" class="pointer-events-none absolute -right-6 -top-10 select-none font-display text-[260px] font-extrabold leading-none text-white/[0.025] md:text-[420px]">AF</div>
    <div class="wrap relative">
        <header class="grid gap-6 md:grid-cols-12 md:items-end">
            <div class="md:col-span-7">
                <p class="kicker">Our differentiator</p>
                <h2 id="africa-h" class="hed mt-2 text-[48px] md:text-[84px]">Africa <em class="font-medium text-gold">in focus</em></h2>
            </div>
            <p class="max-w-md text-[16px] leading-relaxed text-fog md:col-span-5 md:justify-self-end">From Lagos stablecoin desks to Nairobi mobile-money rails: the reporting that explains how digital assets are really used across the continent.</p>
        </header>

        <nav class="no-scrollbar mt-8 flex gap-2 overflow-x-auto" aria-label="Countries">
            @foreach (['Nigeria', 'Ghana', 'Kenya', 'South Africa', 'Rwanda', 'Ethiopia'] as $c)
                <a href="{{ route('country', \Illuminate\Support\Str::slug($c)) }}" class="shrink-0 border border-line-2 px-4 py-2 font-mono text-[11px] uppercase tracking-wider text-fog transition-colors hover:border-gold hover:text-gold">{{ $c }} @isset($countries[$c])<span class="ml-1 text-mute">{{ $countries[$c] }}</span>@endisset</a>
            @endforeach
            <a href="{{ route('africa') }}" class="shrink-0 bg-gold px-4 py-2 font-mono text-[11px] uppercase tracking-wider text-ink transition-colors hover:bg-paper">All Africa →</a>
        </nav>

        @php $lead = $africa->first(); $rest = $africa->slice(1)->values(); @endphp
        <div class="mt-12 grid gap-px bg-line lg:grid-cols-12">
            @if ($lead)
                <article class="group relative min-h-[420px] overflow-hidden bg-ink lg:col-span-7 lg:min-h-[560px]">
                    <a href="{{ $lead->url }}" class="absolute inset-0"><img src="{{ $lead->img(1400, 1000) }}" alt="{{ $lead->image_alt }}" loading="lazy" class="size-full object-cover transition-transform duration-1000 group-hover:scale-[1.04]"></a>
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink via-ink/60 to-transparent"></div>
                    <div class="pointer-events-none absolute inset-x-0 bottom-0 p-6 md:p-10">
                        <span class="inline-block bg-gold px-2.5 py-1 font-mono text-[11px] font-medium uppercase tracking-[0.16em] text-ink">{{ $lead->country ?? 'Africa' }}</span>
                        <h3 class="hed mt-4 text-[30px] md:text-[44px]"><a href="{{ $lead->url }}" class="pointer-events-auto link-hed">{{ $lead->title }}</a></h3>
                        <p class="mt-3 hidden max-w-xl text-[16px] text-paper/80 md:block">{{ $lead->excerpt }}</p>
                        <p class="meta mt-4 !text-paper/60">{{ $lead->author->name ?? '' }} · {{ $lead->published_at?->format('j M Y') }}</p>
                    </div>
                </article>
            @endif
            <div class="grid gap-px bg-line sm:grid-cols-2 lg:col-span-5">
                @foreach ($rest as $a)
                    <article class="group flex flex-col justify-between bg-ink-2 p-6 transition-colors hover:bg-card">
                        <div>
                            <p class="font-display text-[26px] font-bold italic leading-none text-gold">{{ $a->country ?? 'Africa' }}</p>
                            <h3 class="hed mt-4 text-[20px]"><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h3>
                        </div>
                        <p class="meta mt-6">{{ $a->published_at?->format('j M Y') }} · {{ $a->reading_time }} min</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<div class="wrap py-12"><x-ad placement="between_content" class="mx-auto max-w-[970px]" /></div>

{{-- ═══ Market intelligence ═══ --}}
<section class="wrap pb-16 md:pb-24" aria-labelledby="mi-h">
    <header class="flex flex-wrap items-end justify-between gap-4 border-b border-line pb-4">
        <div><p class="kicker">Market intelligence</p><h2 id="mi-h" class="hed mt-1 text-[32px] md:text-[44px]">Bitcoin, ether &amp; the institutions</h2></div>
        <a href="{{ route('markets') }}" class="btn-line !px-4 !py-2.5">Markets desk →</a>
    </header>
    <div class="mt-10 grid gap-10 lg:grid-cols-12">
        <div class="lg:col-span-5">
            @if ($markets->first()) @include('partials.story', ['article' => $markets->first(), 'variant' => 'card', 'ratio' => 'aspect-[4/3]', 'size' => 'text-[30px]', 'w' => 1000]) @endif
        </div>
        <div class="space-y-6 lg:col-span-4 lg:border-x lg:border-line lg:px-8">
            @foreach ($markets->slice(1) as $a)
                <div class="border-b border-line pb-6 last:border-0 last:pb-0">@include('partials.story', ['article' => $a, 'variant' => 'text', 'size' => 'text-[22px]'])</div>
            @endforeach
        </div>
        <div class="lg:col-span-3">@include('partials.market-panel', ['snapshot' => $snapshot])</div>
    </div>
</section>

{{-- ═══ Regulation & policy ═══ --}}
<section class="border-y border-line bg-card py-16 md:py-24" aria-labelledby="reg-h">
    <div class="wrap grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <p class="kicker">Regulation &amp; policy</p>
            <h2 id="reg-h" class="hed mt-2 text-[40px] md:text-[56px]">Who writes<br>the rules</h2>
            <p class="mt-5 max-w-sm text-[16px] leading-relaxed text-fog">Central banks, securities regulators and the licensing regimes that decide how digital assets can operate across Africa.</p>
            <a href="{{ route('regulation') }}" class="btn-gold mt-8">Regulation desk →</a>
        </div>
        <ol class="lg:col-span-8">
            @foreach ($regulation as $i => $a)
                <li class="group grid grid-cols-[48px_1fr] gap-4 border-t border-line-2 py-6 first:border-gold md:grid-cols-[72px_1fr_180px] md:gap-8">
                    <span class="font-mono text-sm text-gold">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <p class="kicker-muted">{{ $a->country ?? 'Pan-African' }}</p>
                        <h3 class="hed mt-1.5 text-[22px] md:text-[28px]"><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h3>
                        <p class="mt-2 line-clamp-2 text-[15px] text-fog">{{ $a->excerpt }}</p>
                    </div>
                    <a href="{{ $a->url }}" class="zoom col-span-2 hidden aspect-[4/3] self-start md:col-span-1 md:block"><img src="{{ $a->img(400, 300) }}" alt="{{ $a->image_alt }}" loading="lazy"></a>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ═══ Insights & analysis ═══ --}}
<section class="wrap py-16 md:py-24" aria-labelledby="in-h">
    <header class="flex flex-wrap items-end justify-between gap-4">
        <div><p class="kicker">Insights &amp; analysis</p><h2 id="in-h" class="hed mt-1 text-[40px] md:text-[64px]">Voices <em class="font-medium text-gold">&amp; views</em></h2></div>
        <a href="{{ route('insights') }}" class="btn-line !px-4 !py-2.5">All insights →</a>
    </header>
    <div class="mt-10 grid gap-px border-y border-line bg-line md:grid-cols-3">
        @foreach ($insights as $a)
            <article class="group flex flex-col bg-ink p-7 md:p-9">
                <span class="font-display text-[80px] font-extrabold leading-[0.6] text-gold/60">“</span>
                <h3 class="hed mt-2 flex-1 text-[24px] md:text-[28px]"><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h3>
                <p class="mt-4 line-clamp-3 text-[15px] leading-relaxed text-fog">{{ $a->excerpt }}</p>
                <div class="mt-7 flex items-center gap-3 border-t border-line pt-5">
                    <img src="{{ $a->author->photo_url }}" alt="" class="size-11 rounded-full object-cover grayscale" loading="lazy" width="44" height="44">
                    <div><p class="text-[14px] font-medium text-paper">{{ $a->author->name }}</p><p class="meta">{{ $a->author->role }}</p></div>
                </div>
            </article>
        @endforeach
    </div>

    @if ($stable->count())
        <div class="mt-16">
            <div class="flex items-baseline justify-between border-t-2 border-gold pt-3"><h3 class="kicker">Stablecoins</h3><a href="{{ route('stablecoins') }}" class="meta transition-colors hover:text-gold">Section →</a></div>
            <div class="mt-6 grid gap-8 md:grid-cols-3">
                @foreach ($stable as $a) @include('partials.story', ['article' => $a, 'variant' => 'card', 'ratio' => 'aspect-[16/10]', 'size' => 'text-[21px]', 'noExcerpt' => true]) @endforeach
            </div>
        </div>
    @endif
</section>

@include('partials.newsletter', ['source' => 'home'])
@endsection
