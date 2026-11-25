@php $s = $snapshot ?? null; @endphp
<section aria-label="Market summary" class="border border-line bg-card">
    <header class="flex items-center justify-between border-b border-line px-5 py-3.5">
        <h3 class="kicker">Market summary</h3>
        @if ($s)<span class="font-mono text-[10px] uppercase tracking-wider text-mute">{{ $s['stale'] ? 'Delayed' : 'Live' }} · USD</span>@endif
    </header>
    @if ($s)
        <ul class="divide-y divide-line">
            @foreach ($s['assets'] as $a)
                @php $up = ($a['change'] ?? 0) >= 0; @endphp
                <li class="flex items-center justify-between gap-3 px-5 py-3">
                    <div class="min-w-0">
                        <p class="font-mono text-[12px] font-medium tracking-wider text-paper">{{ $a['symbol'] }}</p>
                        <p class="truncate text-[12px] text-mute">{{ $a['name'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-mono text-[14px] text-paper">{{ \App\Services\MarketData::formatPrice($a['price']) }}</p>
                        <p class="font-mono text-[11px] {{ $up ? 'text-up' : 'text-down' }}">{{ $up ? '▲' : '▼' }} {{ isset($a['change']) ? ($up ? '+' : '').number_format($a['change'], 2).'%' : '—' }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
        <footer class="border-t border-line px-5 py-3 font-mono text-[10px] uppercase tracking-wider text-mute">
            Updated {{ \Carbon\Carbon::createFromTimestamp($s['updated_at'])->utc()->format('H:i') }} UTC · 24h change · Data: CoinGecko
        </footer>
    @else
        <div class="px-5 py-10 text-center">
            <p class="font-display text-lg text-paper">Prices unavailable</p>
            <p class="mt-1 text-[13px] text-mute">The market-data service isn't responding. We never show placeholder prices. Please check back shortly.</p>
        </div>
    @endif
</section>
