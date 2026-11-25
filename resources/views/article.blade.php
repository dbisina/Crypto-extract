@extends('layouts.app')

@php
    $a = $article;
    $seoTitle = $a->seo_title ?: $a->title;
    $seoDesc = $a->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($a->excerpt ?: $a->body), 155);
    // Insert the in-article ad after the third paragraph.
    $parts = preg_split('/(<\/p>)/i', (string) $a->body, -1, PREG_SPLIT_DELIM_CAPTURE);
    $body = ''; $count = 0; $adAt = null;
    foreach ($parts as $i => $chunk) { $body .= $chunk; if (strtolower($chunk) === '</p>' && ++$count === 3) { $body .= '<!--AD-->'; } }
    [$bodyTop, $bodyBottom] = array_pad(explode('<!--AD-->', $body, 2), 2, '');
    $shareUrl = urlencode($a->url);
    $shareText = urlencode($a->title);
    $crumbs = array_filter([
        ['Home', route('home')],
        $a->category ? [$a->category->name, $a->category->url] : null,
        [\Illuminate\Support\Str::limit($a->title, 48), null],
    ]);
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)
@section('canonical', $a->url)
@section('og_type', 'article')
@section('og_image', $a->og_image_url ?: $a->img(1200, 630))
@section('meta')
    <meta property="article:published_time" content="{{ $a->published_at?->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $a->updated_at->toIso8601String() }}">
    @if ($a->author)<meta property="article:author" content="{{ route('author.show', $a->author) }}">@endif
    @if ($a->category)<meta property="article:section" content="{{ $a->category->name }}">@endif
    @foreach ($a->tags as $t)<meta property="article:tag" content="{{ $t->name }}">@endforeach
    @if ($a->is_sponsored || $a->is_press_release || $a->status !== 'published' && $a->published_at?->isFuture())<meta name="robots" content="{{ $a->is_sponsored ? 'noindex, follow' : 'index, follow' }}">@endif
@endsection

@push('jsonld')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'NewsArticle',
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $a->url],
    'headline' => \Illuminate\Support\Str::limit($seoTitle, 110, ''), 'description' => $seoDesc,
    'image' => array_values(array_filter([$a->og_image_url ?: $a->img(1200, 630)])),
    'datePublished' => $a->published_at?->toIso8601String(), 'dateModified' => $a->updated_at->toIso8601String(),
    'articleSection' => $a->category?->name, 'keywords' => $a->tags->pluck('name')->implode(', '),
    'author' => $a->author ? ['@type' => 'Person', 'name' => $a->author->name, 'url' => route('author.show', $a->author)] : ['@type' => 'Organization', 'name' => 'KryptoExtract'],
    'publisher' => ['@type' => 'NewsMediaOrganization', 'name' => 'KryptoExtract', 'url' => url('/')],
], JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => collect($crumbs)->values()->map(fn ($c, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0], 'item' => $c[1] ?? $a->url])->all(),
], JSON_UNESCAPED_SLASHES) !!}</script>
@if ($a->author)
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'Person', 'name' => $a->author->name, 'jobTitle' => $a->author->role,
    'url' => route('author.show', $a->author), 'image' => $a->author->photo_url, 'description' => $a->author->bio,
    'worksFor' => ['@type' => 'NewsMediaOrganization', 'name' => 'KryptoExtract'],
], JSON_UNESCAPED_SLASHES) !!}</script>
@endif
@endpush

@section('progress')
    <div x-data="readingProgress()" class="fixed inset-x-0 top-0 z-[60] h-[3px] bg-transparent" aria-hidden="true"><div class="h-full bg-gold transition-[width] duration-100" :style="`width:${pct}%`"></div></div>
@endsection

@section('content')
<article>
    {{-- Header --}}
    <header class="wrap pt-6 md:pt-10">
        <nav aria-label="Breadcrumb" class="meta flex flex-wrap items-center gap-2">
            @foreach ($crumbs as $i => $c)
                @if ($c[1])<a href="{{ $c[1] }}" class="transition-colors hover:text-gold">{{ $c[0] }}</a><span aria-hidden="true">/</span>@else<span class="text-fog" aria-current="page">{{ $c[0] }}</span>@endif
            @endforeach
        </nav>

        <div class="mx-auto mt-8 max-w-[880px] text-center md:mt-12">
            <div class="flex flex-wrap items-center justify-center gap-2">
                <a href="{{ $a->category?->url }}" class="kicker transition-colors hover:text-paper">{{ $a->category?->name ?? 'News' }}</a>
                @if ($a->country)<span class="meta">·</span><a href="{{ route('country', \Illuminate\Support\Str::slug($a->country)) }}" class="kicker-muted transition-colors hover:text-gold">{{ $a->country }}</a>@endif
                @if ($a->is_sponsored)<span class="badge-sponsored">Sponsored</span>@elseif ($a->is_press_release)<span class="badge-press">Press release</span>@elseif ($a->is_breaking)<span class="badge-breaking">Breaking</span>@endif
            </div>
            <h1 class="hed mt-5 text-[36px] sm:text-[48px] lg:text-[62px]">{{ $a->title }}</h1>
            @if ($a->excerpt)<p class="mx-auto mt-6 max-w-[720px] text-[19px] leading-relaxed text-fog md:text-[22px]">{{ $a->excerpt }}</p>@endif

            <div class="mt-8 flex flex-col items-center justify-center gap-4 border-y border-line py-5 sm:flex-row sm:gap-8">
                @if ($a->author)
                    <a href="{{ route('author.show', $a->author) }}" class="flex items-center gap-3 text-left">
                        <img src="{{ $a->author->photo_url }}" alt="Portrait of {{ $a->author->name }}" class="size-12 rounded-full object-cover" width="48" height="48">
                        <span><span class="block text-[15px] font-medium text-paper">{{ $a->author->name }}</span><span class="meta">{{ $a->author->role }}</span></span>
                    </a>
                @endif
                <p class="meta leading-loose">
                    <time datetime="{{ $a->published_at?->toIso8601String() }}">Published {{ $a->published_at?->format('j M Y · H:i') }} UTC</time><br class="hidden sm:block">
                    @if ($a->updated_label)<span class="sm:ml-0">Updated {{ $a->updated_label }} · </span>@endif{{ $a->reading_time }} min read
                </p>
            </div>
        </div>
    </header>

    {{-- Label notices --}}
    @if ($a->is_sponsored || $a->is_press_release)
        <div class="wrap mt-8">
            <div class="mx-auto max-w-[760px] border {{ $a->is_sponsored ? 'border-gold bg-gold/10' : 'border-line-2 bg-card' }} px-5 py-4 text-[14px] leading-relaxed text-fog">
                @if ($a->is_sponsored)
                    <strong class="font-mono text-[11px] uppercase tracking-wider text-gold">Sponsored content</strong> — paid for by {{ $a->sponsor_name ?: 'an advertiser' }}. It was not written by the KryptoExtract newsroom and does not represent our editorial view. <a href="{{ route('transparency') }}" class="text-gold underline">How we handle sponsored content</a>.
                @else
                    <strong class="font-mono text-[11px] uppercase tracking-wider text-paper">Press release</strong> — published as supplied by the issuer. KryptoExtract has not independently verified its claims. <a href="{{ route('editorial') }}" class="text-gold underline">Editorial policy</a>.
                @endif
            </div>
        </div>
    @endif

    {{-- Featured image --}}
    @if ($a->image_url)
        <figure class="wrap mt-10">
            <div class="mx-auto max-w-[1100px]">
                <div class="zoom aspect-[16/9] md:aspect-[2/1]"><img src="{{ $a->img(1800, 900) }}" alt="{{ $a->image_alt }}" fetchpriority="high"></div>
                @if ($a->image_caption)<figcaption class="meta mt-3 max-w-[760px]">{{ $a->image_caption }}</figcaption>@endif
            </div>
        </figure>
    @endif

    {{-- Body + share rail --}}
    <div class="wrap mt-12">
        <div class="mx-auto grid max-w-[1000px] gap-8 lg:grid-cols-[64px_1fr] lg:gap-12">
            <aside class="lg:order-first">
                <div x-data="copyLink()" class="flex gap-2 lg:sticky lg:top-8 lg:flex-col" aria-label="Share this article">
                    <span class="meta hidden lg:block">Share</span>
                    <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="share" aria-label="Share on WhatsApp" title="WhatsApp">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3.5A11.8 11.8 0 0 0 12 0C5.4 0 .1 5.3.1 11.9c0 2.1.6 4.2 1.6 6L0 24l6.3-1.7a12 12 0 0 0 5.700 1.5c6.600 0 11.900-5.300 11.900-11.900 0-3.200-1.200-6.200-3.400-8.400ZM12 21.800c-1.800 0-3.600-.5-5.100-1.400l-.4-.2-3.700 1 1-3.600-.2-.4A9.900 9.900 0 0 1 2.100 12C2.100 6.500 6.500 2.100 12 2.100c2.600 0 5.100 1 7 2.900a9.800 9.800 0 0 1 2.900 7c0 5.500-4.400 9.800-9.900 9.800Zm5.400-7.400c-.3-.1-1.800-.9-2-1s-.5-.1-.7.100l-.9 1.100c-.2.200-.3.200-.6.100a8 8 0 0 1-4-3.500c-.3-.5.300-.5.900-1.600.1-.2 0-.4 0-.5l-.9-2.200c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.100-.8.400-.3.300-1 1-1 2.500s1.100 2.900 1.200 3.100c.1.200 2.100 3.200 5.100 4.500 1.900.8 2.600.9 3.600.7.600-.1 1.800-.7 2-1.400.3-.7.300-1.300.2-1.400-.1-.1-.3-.2-.6-.3Z"/></svg>
                    </a>
                    <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener" class="share" aria-label="Share on Telegram" title="Telegram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.900 3.600 20.300 20.500c-.3 1.200-1 1.500-2 .9l-5.500-4-2.600 2.600c-.3.300-.5.500-1.100.5l.4-5.600L19.700 6c.5-.4-.1-.7-.7-.3L6.500 13.600 1.100 11.900c-1.200-.4-1.200-1.200.2-1.700L22.400 2c1-.4 1.800.2 1.500 1.600Z"/></svg>
                    </a>
                    <a href="https://x.com/intent/post?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener" class="share" aria-label="Share on X" title="X">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.200 2h3.400l-7.400 8.500L23 22h-6.800l-5.300-7-6.100 7H1.400l7.900-9.100L1 2h7l4.800 6.400L18.200 2Zm-1.200 18h1.900L7 3.900H5L17 20Z"/></svg>
                    </a>
                    <button type="button" @click="copy(@js($a->url))" class="share" aria-label="Copy link" title="Copy link">
                        <svg x-show="!done" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 14a4 4 0 0 0 5.700 0l3-3a4 4 0 0 0-5.700-5.700l-1 1M14 10a4 4 0 0 0-5.700 0l-3 3a4 4 0 0 0 5.700 5.700l1-1"/></svg>
                        <svg x-cloak x-show="done" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m5 12 5 5 9-10"/></svg>
                    </button>
                    <span x-cloak x-show="done" x-transition class="meta absolute mt-12 hidden lg:block">Copied</span>
                </div>
            </aside>

            <div id="article-body" class="min-w-0 max-w-[720px]">
                <div class="prose-kx drop">{!! $bodyTop !!}</div>
                <x-ad placement="in_article" class="my-10" />
                <div class="prose-kx">{!! $bodyBottom !!}</div>

                @if ($a->sources)
                    <section class="mt-12 border-t border-line pt-6" aria-labelledby="src">
                        <h2 id="src" class="kicker">Sources &amp; references</h2>
                        <ol class="mt-4 list-decimal space-y-2 pl-5 text-[14px] leading-relaxed text-fog marker:font-mono marker:text-gold">
                            @foreach (preg_split('/\r?\n/', trim($a->sources)) as $line)
                                @if (trim($line) !== '')
                                    <li>@if (\Illuminate\Support\Str::startsWith(trim($line), ['http://', 'https://']))<a href="{{ trim($line) }}" rel="noopener nofollow" target="_blank" class="underline decoration-gold/40 underline-offset-4 hover:text-gold">{{ trim($line) }}</a>@else{{ trim($line) }}@endif</li>
                                @endif
                            @endforeach
                        </ol>
                    </section>
                @endif

                @if ($a->tags->count())
                    <div class="mt-8 flex flex-wrap gap-2">
                        @foreach ($a->tags as $t)<a href="{{ route('search', ['q' => $t->name]) }}" class="border border-line-2 px-3 py-1.5 font-mono text-[11px] uppercase tracking-wider text-fog transition-colors hover:border-gold hover:text-gold">{{ $t->name }}</a>@endforeach
                    </div>
                @endif

                @if ($a->author)
                    <aside class="mt-12 flex gap-5 border border-line bg-card p-6">
                        <img src="{{ $a->author->photo_url }}" alt="" class="size-16 shrink-0 rounded-full object-cover" loading="lazy" width="64" height="64">
                        <div>
                            <p class="kicker">About the author</p>
                            <h2 class="font-display text-[22px] font-bold"><a href="{{ route('author.show', $a->author) }}" class="hover:text-gold">{{ $a->author->name }}</a></h2>
                            <p class="meta mt-0.5">{{ $a->author->role }}</p>
                            <p class="mt-3 text-[15px] leading-relaxed text-fog">{{ $a->author->bio }}</p>
                            <div class="mt-3 flex gap-4">
                                @if ($a->author->twitter)<a href="https://x.com/{{ ltrim($a->author->twitter, '@') }}" rel="noopener me" class="meta hover:text-gold">{{ $a->author->twitter }}</a>@endif
                                <a href="{{ route('author.show', $a->author) }}" class="meta hover:text-gold">More from {{ explode(' ', $a->author->name)[0] }} →</a>
                            </div>
                        </div>
                    </aside>
                @endif

                @if ($a->is_demo)<p class="meta mt-8 border-t border-line pt-5">Demonstration content — this article is a placeholder to showcase the KryptoExtract publication design and is not real reporting.</p>@endif
            </div>
        </div>
    </div>
</article>

<div class="wrap mt-16"><x-ad placement="between_content" class="mx-auto max-w-[970px]" /></div>

{{-- Related --}}
@if ($related->count())
    <section class="wrap mt-16 pb-20" aria-labelledby="rel">
        <div class="flex items-baseline justify-between border-t-2 border-gold pt-3"><h2 id="rel" class="kicker">Keep reading</h2><a href="{{ route('latest') }}" class="meta hover:text-gold">Latest →</a></div>
        <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($related as $r) @include('partials.story', ['article' => $r, 'variant' => 'card', 'ratio' => 'aspect-[4/3]', 'size' => 'text-[19px]', 'noExcerpt' => true, 'w' => 600]) @endforeach
        </div>
    </section>
@endif

@include('partials.newsletter', ['source' => 'article'])
@endsection
