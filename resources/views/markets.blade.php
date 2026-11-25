@extends('layouts.app')
@section('title', 'Crypto Markets: Prices, Analysis & Institutional Flows')
@section('description', 'Live prices for BTC, ETH, BNB, SOL, USDT and USDC with market analysis, institutional activity and trading developments.')

@section('content')
@include('partials.page-head', ['eyebrow' => 'Markets desk', 'title' => 'Markets', 'crumbs' => [['Markets']],
    'intro' => 'Prices, flows and the structure of crypto markets, with a focus on how African venues and investors are positioned.'])

<section class="wrap py-12">
    @if ($snapshot)
        <div class="grid gap-px bg-line sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($snapshot['assets'] as $a)
                @php $up = ($a['change'] ?? 0) >= 0; @endphp
                <div class="bg-ink-2 p-6">
                    <div class="flex items-baseline justify-between"><span class="font-mono text-sm font-medium tracking-wider text-paper">{{ $a['symbol'] }}</span><span class="meta">{{ $a['name'] }}</span></div>
                    <p class="mt-5 font-mono text-[34px] leading-none text-paper">{{ \App\Services\MarketData::formatPrice($a['price']) }}</p>
                    <p class="mt-3 font-mono text-sm {{ $up ? 'text-up' : 'text-down' }}">{{ $up ? '▲ +' : '▼ ' }}{{ isset($a['change']) ? number_format($a['change'], 2).'%' : '—' }} <span class="text-mute">24h</span></p>
                </div>
            @endforeach
        </div>
        <p class="meta mt-4">Updated {{ \Carbon\Carbon::createFromTimestamp($snapshot['updated_at'])->utc()->format('j M Y, H:i') }} UTC{{ $snapshot['stale'] ? ' · delayed, refresh failed' : '' }} · USD · Source: CoinGecko. For information only; not investment advice.</p>
    @else
        <div class="border border-line bg-card px-6 py-14 text-center">
            <p class="font-display text-2xl">Live prices are currently unavailable</p>
            <p class="mx-auto mt-2 max-w-md text-fog">We can't reach the market-data provider right now, and we never display placeholder prices. Please refresh in a minute.</p>
        </div>
    @endif
</section>

<section class="wrap pb-16">
    <div class="grid gap-12 lg:grid-cols-12">
        <div class="divide-y divide-line lg:col-span-8">
            <h2 class="kicker border-t-2 border-gold pb-6 pt-3">Market coverage</h2>
            @foreach ($articles as $a)<div class="py-7">@include('partials.story', ['article' => $a, 'variant' => 'row'])</div>@endforeach
        </div>
        <aside class="space-y-10 lg:col-span-4"><x-ad placement="homepage_sidebar" class="mx-auto w-full max-w-[300px]" /></aside>
    </div>
    {{ $articles->links() }}
</section>
@endsection
