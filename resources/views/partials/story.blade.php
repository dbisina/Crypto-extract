{{-- Story presentation variants: card | row | text | wide | lead --}}
@php
    $v = $variant ?? 'card';
    $a = $article;
    $time = $a->published_at?->diffForHumans(short: true) ;
@endphp

@php
    $chips = function () use ($a) {
        $out = '<span class="kicker">'.e($a->country ?: ($a->category->name ?? 'News')).'</span>';
        if ($a->is_sponsored) $out .= ' <span class="badge-sponsored">Sponsored</span>';
        elseif ($a->is_press_release) $out .= ' <span class="badge-press">Press release</span>';
        elseif ($a->is_breaking) $out .= ' <span class="badge-breaking">Breaking</span>';
        return $out;
    };
@endphp

@if ($v === 'lead')
    <article class="group relative">
        <a href="{{ $a->url }}" class="block">
            <div class="zoom aspect-[4/3] md:aspect-[16/10]"><img src="{{ $a->img(1400, 900) }}" alt="{{ $a->image_alt }}" fetchpriority="high"></div>
        </a>
        <div class="mt-5 flex flex-wrap items-center gap-2">{!! $chips() !!}<span class="meta">· {{ $a->category->name ?? '' }}</span></div>
        <h2 class="hed mt-3 text-[34px] sm:text-[44px] lg:text-[56px]"><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h2>
        <p class="mt-4 max-w-2xl text-[17px] leading-relaxed text-fog md:text-[19px]">{{ $a->excerpt }}</p>
        <p class="meta mt-5">By <span class="text-paper">{{ $a->author->name ?? 'KryptoExtract' }}</span> · {{ $a->published_at?->format('j M Y') }} · {{ $a->reading_time }} min read</p>
    </article>

@elseif ($v === 'card')
    <article class="group">
        <a href="{{ $a->url }}" class="block"><div class="zoom {{ $ratio ?? 'aspect-[3/2]' }}"><img src="{{ $a->img($w ?? 800, $h ?? null) }}" alt="{{ $a->image_alt }}" loading="lazy"></div></a>
        <div class="mt-4 flex flex-wrap items-center gap-2">{!! $chips() !!}</div>
        <h3 class="hed mt-2 {{ $size ?? 'text-[22px]' }}"><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h3>
        @if (empty($noExcerpt))<p class="mt-2 line-clamp-3 text-[15px] leading-relaxed text-fog">{{ $a->excerpt }}</p>@endif
        <p class="meta mt-3">{{ $a->author->name ?? '' }} · {{ $time }}</p>
    </article>

@elseif ($v === 'row')
    @php $cp = ! empty($compact); @endphp
    <article @class(['group grid gap-4', 'grid-cols-[1fr_84px]' => $cp, 'grid-cols-[1fr_96px] sm:grid-cols-[1fr_168px] sm:gap-6' => ! $cp])>
        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">{!! $chips() !!}</div>
            <h3 @class(['hed mt-2', 'text-[18px]' => $cp, 'text-[19px] sm:text-[24px]' => ! $cp])><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h3>
            @if (empty($noExcerpt))<p class="mt-2 line-clamp-2 hidden text-[15px] leading-relaxed text-fog sm:block">{{ $a->excerpt }}</p>@endif
            <p class="meta mt-3">{{ $a->author->name ?? '' }} · {{ $a->published_at?->format('j M Y') }}</p>
        </div>
        <a href="{{ $a->url }}" class="block self-start"><div @class(['zoom aspect-square', 'sm:aspect-[4/3]' => ! $cp])><img src="{{ $a->img(400, 300) }}" alt="{{ $a->image_alt }}" loading="lazy"></div></a>
    </article>

@elseif ($v === 'wide')
    <article class="group grid gap-5 md:grid-cols-[1.2fr_1fr] md:gap-8">
        <a href="{{ $a->url }}" class="block"><div class="zoom aspect-[16/10]"><img src="{{ $a->img(1000, 625) }}" alt="{{ $a->image_alt }}" loading="lazy"></div></a>
        <div class="self-center">
            <div class="flex flex-wrap items-center gap-2">{!! $chips() !!}</div>
            <h3 class="hed mt-3 text-[28px] md:text-[34px]"><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h3>
            <p class="mt-3 text-[16px] leading-relaxed text-fog">{{ $a->excerpt }}</p>
            <p class="meta mt-4">{{ $a->author->name ?? '' }} · {{ $a->published_at?->format('j M Y') }} · {{ $a->reading_time }} min</p>
        </div>
    </article>

@else {{-- text --}}
    <article class="group flex gap-4">
        @isset($n)<span class="font-display text-[34px] font-bold leading-none text-gold/70">{{ $n }}</span>@endisset
        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">{!! $chips() !!}</div>
            <h3 class="hed mt-1.5 {{ $size ?? 'text-[19px]' }}"><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h3>
            <p class="meta mt-2">{{ $time }}</p>
        </div>
    </article>
@endif
