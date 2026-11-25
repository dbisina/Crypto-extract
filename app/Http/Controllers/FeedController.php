<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;

class FeedController extends Controller
{
    public function sitemap()
    {
        $urls = collect([
            route('home'), route('latest'), route('africa'), route('markets'), route('bitcoin'), route('regulation'),
            route('stablecoins'), route('defi'), route('insights'), route('learn'), route('about'), route('editorial'),
            route('corrections'), route('transparency'), route('advertise'), route('contact'), route('tip'),
        ])->map(fn ($u) => ['loc' => $u, 'lastmod' => now()->toDateString()]);

        $urls = $urls
            ->merge(Category::all()->map(fn ($c) => ['loc' => route('category.show', $c), 'lastmod' => now()->toDateString()]))
            ->merge(Author::all()->map(fn ($a) => ['loc' => route('author.show', $a), 'lastmod' => $a->updated_at->toDateString()]))
            ->merge(Article::live()->latestFirst()->get()->map(fn ($a) => ['loc' => $a->url, 'lastmod' => $a->updated_at->toDateString()]));

        return response()->view('feeds.sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }

    public function newsSitemap()
    {
        $articles = Article::live()->where('published_at', '>=', now()->subDays(2))->where('is_press_release', false)->latestFirst()->get();
        return response()->view('feeds.news-sitemap', compact('articles'))->header('Content-Type', 'application/xml');
    }

    public function rss()
    {
        $articles = Article::live()->with(['author', 'category'])->latestFirst()->limit(30)->get();
        return response()->view('feeds.rss', compact('articles'))->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }

    public function robots()
    {
        $txt = "User-agent: *\nDisallow: /admin\nDisallow: /search\nDisallow: /ad/\nDisallow: /api/\n\nSitemap: ".route('sitemap')."\nSitemap: ".route('sitemap.news')."\n";
        return response($txt)->header('Content-Type', 'text/plain');
    }
}
