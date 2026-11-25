@props(['placement'])
@php
    $ad = \App\Models\Advertisement::forPlacement($placement);
    $sizes = [
        'header_leaderboard' => 'min-h-[70px] md:min-h-[90px]', 'homepage_banner' => 'min-h-[110px] md:min-h-[160px]',
        'homepage_sidebar' => 'min-h-[250px]', 'in_article' => 'min-h-[90px]', 'between_content' => 'min-h-[100px] md:min-h-[120px]',
        'footer' => 'min-h-[90px]', 'newsletter_sponsor' => 'min-h-[56px]',
    ];
@endphp
@if ($ad)
    <aside {{ $attributes->class(['ad-slot', $sizes[$placement] ?? '']) }} aria-label="Advertisement">
        <span class="ad-label">Advertisement</span>
        <a href="{{ route('ad.click', $ad) }}" rel="sponsored noopener" target="_blank" class="group flex h-full min-h-[inherit] w-full items-center justify-center">
            @if ($ad->banner_url)
                <img src="{{ $ad->banner_url }}" alt="{{ $ad->headline ?: $ad->advertiser }}" loading="lazy" class="max-h-full max-w-full object-contain">
            @else
                <span class="flex flex-col items-center gap-1.5 px-6 py-5 text-center">
                    <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-mute">{{ $ad->advertiser }}</span>
                    <span class="font-display text-lg font-bold leading-snug text-paper transition-colors group-hover:text-gold md:text-2xl">{{ $ad->headline ?: $ad->name }}</span>
                    <span class="mt-1 font-mono text-[10px] uppercase tracking-[0.2em] text-gold">Learn more →</span>
                </span>
            @endif
        </a>
    </aside>
@endif
