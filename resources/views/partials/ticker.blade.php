@php $initial = $snapshot ? ['assets' => $snapshot['assets'], 'updated_at' => $snapshot['updated_at'], 'stale' => $snapshot['stale']] : null; @endphp
<div x-data="ticker(@js($initial))" class="border-b border-line bg-ink-2" role="region" aria-label="Cryptocurrency prices">
    <div class="wrap flex items-stretch">
        <div class="flex shrink-0 items-center gap-2 border-r border-line pr-4">
            <span class="kx-live inline-block size-1.5 rounded-full bg-gold" x-show="state && !state.stale"></span>
            <span class="kicker !text-fog">Markets</span>
        </div>

        {{-- Available --}}
        <template x-if="state">
            <div class="relative min-w-0 flex-1 overflow-hidden">
                <div class="kx-marquee flex w-max items-center py-2.5">
                    <template x-for="n in 2">
                        <div class="flex items-center">
                            <template x-for="a in state.assets" :key="a.id + n">
                                <div class="flex items-baseline gap-2 whitespace-nowrap px-5">
                                    <span class="font-mono text-[11px] font-medium tracking-wider text-paper" x-text="a.symbol"></span>
                                    <span class="font-mono text-[12px] text-fog" x-text="price(a.price)"></span>
                                    <span class="flex items-center gap-1 font-mono text-[11px]" :class="a.change >= 0 ? 'text-up' : 'text-down'">
                                        <svg width="8" height="8" viewBox="0 0 8 8" fill="currentColor"><path x-show="a.change >= 0" d="M4 0 8 7H0z"/><path x-show="a.change < 0" d="M4 8 0 1h8z"/></svg>
                                        <span x-text="pct(a.change)"></span>
                                    </span>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                <div class="pointer-events-none absolute inset-y-0 left-0 w-8 bg-gradient-to-r from-ink-2 to-transparent"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-ink-2 to-transparent"></div>
            </div>
        </template>

        {{-- Unavailable --}}
        <template x-if="!state">
            <div class="flex flex-1 items-center gap-3 px-5 py-2.5">
                <span class="font-mono text-[11px] text-mute">Live prices are temporarily unavailable.</span>
                <button @click="refresh()" class="font-mono text-[11px] uppercase tracking-wider text-gold hover:text-paper" x-text="loading ? 'Retrying…' : 'Retry'"></button>
            </div>
        </template>

        <div class="hidden shrink-0 items-center border-l border-line pl-4 lg:flex">
            <span class="font-mono text-[10px] uppercase tracking-wider text-mute" x-show="state" x-text="(state && state.stale ? 'Delayed · ' : 'Updated ') + updated() + ' · USD, 24h · CoinGecko'"></span>
        </div>
    </div>
</div>
