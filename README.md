# KryptoExtract

Premium African cryptocurrency & financial news publication. Laravel 13 · Blade · SQLite · Tailwind CSS 4 · Alpine.js · Vite.

## Run locally

```powershell
composer install
npm install
copy .env.example .env      # then: php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
npm run build               # or `npm run dev` for hot reload
php artisan serve           # http://127.0.0.1:8000
```

PHP needs the `pdo_sqlite`, `mbstring`, `fileinfo`, `curl`, `openssl`, `gd` extensions and a CA bundle (`curl.cainfo` / `openssl.cafile`) so the CoinGecko request can use HTTPS.

## CMS

`/admin` — demo login `admin@kryptoextract.test` / `password` (created by the seeder). **Change or remove before deploying.**

Articles (Trix rich-text editor, drafts, scheduling, SEO fields, flags for featured / breaking / sponsored / press release), categories, authors, advertising, media library, and an inbox for subscribers, contact messages and tips.

Scheduled articles go live automatically once their publish time passes. No cron job is needed.

## Market data

`App\Services\MarketData` fetches CoinGecko's public API, caches for 2 minutes, serves the last good snapshot as "delayed" on failure, and shows an "unavailable" state when nothing is cached. It never fabricates prices.

## SEO

Dynamic titles/descriptions, canonical URLs, Open Graph + Twitter cards, NewsArticle / Organization / Person / BreadcrumbList JSON-LD, `/sitemap.xml`, `/news-sitemap.xml`, `/feed.xml` (RSS), `/robots.txt`. Search and admin are `noindex`; sponsored articles are `noindex, follow`.

## Demo content

All seeded articles, authors, advertisers and quotes are **demonstration content**, not real reporting. Photography is hot-linked from Unsplash. Re-run the seeder or delete via the CMS before launch. The Privacy and Terms pages are templates and need legal review.
