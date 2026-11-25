<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Services\MarketData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    private function stories()
    {
        return Article::live()->with(['category', 'author'])->latestFirst();
    }

    public function home(MarketData $market)
    {
        $featured = $this->stories()->where('is_featured', true)->first() ?? $this->stories()->first();
        $skip = [$featured?->id];

        $secondary = $this->stories()->whereNotIn('id', $skip)->where('kind', 'news')->limit(4)->get();
        $skip = array_merge($skip, $secondary->pluck('id')->all());

        $latest = $this->stories()->whereNotIn('id', $skip)->where('kind', 'news')->limit(8)->get();
        $skip = array_merge($skip, $latest->pluck('id')->all());

        $africa = Article::live()->with(['category', 'author'])->latestFirst()
            ->where(fn ($q) => $q->whereNotNull('country')->orWhereHas('category', fn ($c) => $c->where('slug', 'africa')))
            ->where('kind', 'news')->limit(5)->get();

        $markets = $this->stories()->inCategory('markets')->limit(4)->get();
        $regulation = $this->stories()->inCategory('regulation')->limit(4)->get();
        $insights = $this->stories()->where('kind', 'insight')->limit(3)->get();
        $stable = $this->stories()->inCategory('stablecoins')->limit(3)->get();

        $countries = Article::live()->whereNotNull('country')->selectRaw('country, count(*) as n')->groupBy('country')->orderByDesc('n')->pluck('n', 'country');

        return view('home', compact('featured', 'secondary', 'latest', 'africa', 'markets', 'regulation', 'insights', 'stable', 'countries') + ['snapshot' => $market->snapshot()]);
    }

    public function latest(Request $r)
    {
        $articles = $this->stories()->paginate(14);
        return view('listing', [
            'title' => 'Latest news', 'eyebrow' => 'Archive', 'intro' => 'Every story from the KryptoExtract newsroom, newest first.',
            'articles' => $articles, 'crumbs' => [['Latest']], 'seoTitle' => 'Latest Crypto & Digital Asset News',
        ]);
    }

    public function article(Article $article)
    {
        abort_unless(in_array($article->status, ['published', 'scheduled']) && $article->published_at?->lte(now()) || auth()->user()?->is_admin, 404);
        $article->load(['category', 'author', 'tags']);
        return view('article', ['article' => $article, 'related' => $article->related(4)]);
    }

    public function category(Category $category)
    {
        $articles = $this->stories()->where('category_id', $category->id)->paginate(14);
        return view('listing', [
            'title' => $category->name, 'eyebrow' => 'Category', 'intro' => $category->description,
            'articles' => $articles, 'crumbs' => [[$category->name]], 'seoTitle' => $category->name.' News',
        ]);
    }

    public function author(Author $author)
    {
        $articles = $this->stories()->where('author_id', $author->id)->paginate(12);
        return view('author', compact('author', 'articles'));
    }

    public function search(Request $r)
    {
        $q = trim((string) $r->query('q', ''));
        $articles = $q === '' ? null : $this->stories()->where(function ($w) use ($q) {
            $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $q).'%';
            $w->where('title', 'like', $like)->orWhere('excerpt', 'like', $like)->orWhere('body', 'like', $like)
              ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', $like));
        })->paginate(12)->withQueryString();

        return view('search', ['q' => $q, 'articles' => $articles]);
    }

    public function africa()
    {
        $base = fn () => Article::live()->with(['category', 'author'])->latestFirst()
            ->where(fn ($q) => $q->whereNotNull('country')->orWhereHas('category', fn ($c) => $c->where('slug', 'africa')));
        $lead = $base()->first();
        $rest = $base()->when($lead, fn ($q) => $q->where('id', '!=', $lead->id))->paginate(12);
        $counts = Article::live()->whereNotNull('country')->selectRaw('country, count(*) as n')->groupBy('country')->pluck('n', 'country');
        return view('africa', compact('lead', 'rest', 'counts'));
    }

    public function country(string $country)
    {
        $name = collect(Article::COUNTRIES)->first(fn ($c) => Str::slug($c) === $country);
        abort_unless($name, 404);
        $articles = $this->stories()->country($name)->paginate(12);
        return view('listing', [
            'title' => $name, 'eyebrow' => 'Africa in focus', 'intro' => "Cryptocurrency, stablecoin and digital-finance coverage from {$name}.",
            'articles' => $articles, 'crumbs' => [['Africa', route('africa')], [$name]], 'seoTitle' => "{$name}: Crypto & Digital Finance News", 'country' => $name,
        ]);
    }

    public function section(string $slug)
    {
        $cfg = [
            'regulation' => ['Regulation', 'Central banks, securities regulators, licensing regimes and the law of digital assets.', 'Regulation & Policy'],
            'stablecoins' => ['Stablecoins', 'Dollar tokens, local-currency coins and the payment rails being built on them.', 'Stablecoin News & Analysis'],
            'defi' => ['DeFi', 'Lending, exchanges and on-chain liquidity: the decentralised financial system.', 'DeFi News'],
        ][$slug];
        $articles = $this->stories()->inCategory($slug)->paginate(12);
        return view('listing', [
            'title' => $cfg[0], 'eyebrow' => 'Section', 'intro' => $cfg[1], 'articles' => $articles,
            'crumbs' => [[$cfg[0]]], 'seoTitle' => $cfg[2], 'withLead' => true,
        ]);
    }

    public function markets(MarketData $m)
    {
        $articles = $this->stories()->inCategory('markets')->paginate(12);
        return view('markets', ['articles' => $articles, 'snapshot' => $m->snapshot()]);
    }

    public function bitcoin(MarketData $m)
    {
        $articles = $this->stories()->where(fn ($q) => $q->inCategory('bitcoin')
            ->orWhereHas('tags', fn ($t) => $t->where('slug', 'bitcoin')))->paginate(12);
        return view('listing', [
            'title' => 'Bitcoin', 'eyebrow' => 'Section', 'intro' => 'Bitcoin, miners, ETFs and the macro forces behind the largest digital asset.',
            'articles' => $articles, 'crumbs' => [['Bitcoin']], 'seoTitle' => 'Bitcoin News & Analysis', 'withLead' => true,
            'snapshot' => $m->snapshot(),
        ]);
    }

    public function insights()
    {
        $articles = $this->stories()->where('kind', 'insight')->paginate(10);
        return view('insights', compact('articles'));
    }

    public function learn()
    {
        $articles = $this->stories()->where('kind', 'guide')->paginate(12);
        return view('learn', compact('articles'));
    }

    public function market(MarketData $m)
    {
        $snap = $m->snapshot();
        return response()->json(['data' => $snap])->header('Cache-Control', 'public, max-age=30');
    }

    public function adClick(Advertisement $advertisement)
    {
        $advertisement->increment('clicks');
        return redirect()->away($advertisement->url);
    }

    public function contact() { return view('pages.contact'); }
    public function tip() { return view('pages.tip'); }

    public function page(string $page)
    {
        return view('pages.'.$page);
    }
}
