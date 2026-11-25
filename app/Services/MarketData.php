<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Live prices from CoinGecko's public API, cached server-side.
 * Never returns fabricated prices: on failure it serves the last good
 * snapshot (marked stale) or null.
 */
class MarketData
{
    public const ASSETS = [
        'bitcoin' => ['BTC', 'Bitcoin'],
        'ethereum' => ['ETH', 'Ethereum'],
        'binancecoin' => ['BNB', 'BNB'],
        'solana' => ['SOL', 'Solana'],
        'tether' => ['USDT', 'Tether'],
        'usd-coin' => ['USDC', 'USD Coin'],
    ];

    private const FRESH = 120;   // seconds a snapshot is considered fresh
    private const KEEP = 86400;  // how long we keep a stale fallback

    /** @return array{assets: array, updated_at: int, stale: bool}|null */
    public function snapshot(): ?array
    {
        $cached = Cache::get('market.snapshot');
        if ($cached && (time() - $cached['updated_at']) < self::FRESH) {
            return $cached + ['stale' => false];
        }

        // Throttle failed attempts so a dead API doesn't slow every page.
        if (! Cache::has('market.backoff')) {
            $fresh = $this->fetch();
            if ($fresh) {
                Cache::put('market.snapshot', $fresh, self::KEEP);
                return $fresh + ['stale' => false];
            }
            Cache::put('market.backoff', 1, 60);
        }

        return $cached ? $cached + ['stale' => true] : null;
    }

    private function fetch(): ?array
    {
        try {
            $res = Http::timeout(4)->acceptJson()->get('https://api.coingecko.com/api/v3/simple/price', [
                'ids' => implode(',', array_keys(self::ASSETS)),
                'vs_currencies' => 'usd',
                'include_24hr_change' => 'true',
                'include_market_cap' => 'true',
            ]);
            if (! $res->successful()) return null;
            $data = $res->json();

            $assets = [];
            foreach (self::ASSETS as $id => [$symbol, $name]) {
                if (! isset($data[$id]['usd'])) continue;
                $assets[] = [
                    'id' => $id, 'symbol' => $symbol, 'name' => $name,
                    'price' => (float) $data[$id]['usd'],
                    'change' => isset($data[$id]['usd_24h_change']) ? (float) $data[$id]['usd_24h_change'] : null,
                    'market_cap' => $data[$id]['usd_market_cap'] ?? null,
                ];
            }
            return $assets ? ['assets' => $assets, 'updated_at' => time()] : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public static function formatPrice(float $p): string
    {
        return '$'.number_format($p, $p >= 100 ? 0 : ($p >= 1.1 ? 2 : 4));
    }
}
