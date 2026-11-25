@php
    $live = in_array($article->status, ['published', 'scheduled']) && $article->published_at?->lte(now());
    [$label, $cls] = match (true) {
        $live => ['Published', 'border-up/60 text-up'],
        $article->status === 'scheduled' || ($article->status === 'published' && $article->published_at?->isFuture()) => ['Scheduled', 'border-gold/60 text-gold'],
        default => ['Draft', 'border-line-2 text-fog'],
    };
@endphp
<span class="inline-block border px-2 py-[3px] font-mono text-[10px] uppercase tracking-[0.14em] {{ $cls }}">{{ $label }}</span>
