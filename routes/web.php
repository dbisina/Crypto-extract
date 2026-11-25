<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/latest', [SiteController::class, 'latest'])->name('latest');
Route::get('/news/{article:slug}', [SiteController::class, 'article'])->name('article.show');
Route::get('/category/{category:slug}', [SiteController::class, 'category'])->name('category.show');
Route::get('/author/{author:slug}', [SiteController::class, 'author'])->name('author.show');
Route::get('/search', [SiteController::class, 'search'])->name('search');

Route::get('/africa', [SiteController::class, 'africa'])->name('africa');
Route::get('/africa/{country}', [SiteController::class, 'country'])->name('country')->where('country', '[a-z\-]+');

Route::get('/markets', [SiteController::class, 'markets'])->name('markets');
Route::get('/bitcoin', [SiteController::class, 'bitcoin'])->name('bitcoin');
Route::get('/regulation', fn () => app(SiteController::class)->section('regulation'))->name('regulation');
Route::get('/stablecoins', fn () => app(SiteController::class)->section('stablecoins'))->name('stablecoins');
Route::get('/defi', fn () => app(SiteController::class)->section('defi'))->name('defi');
Route::get('/insights', [SiteController::class, 'insights'])->name('insights');
Route::get('/learn', [SiteController::class, 'learn'])->name('learn');

Route::get('/api/market', [SiteController::class, 'market'])->name('api.market');
Route::get('/ad/{advertisement}', [SiteController::class, 'adClick'])->name('ad.click');

Route::post('/newsletter', [FormController::class, 'newsletter'])->name('newsletter.store')->middleware('throttle:10,1');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [FormController::class, 'contact'])->name('contact.store')->middleware('throttle:5,1');
Route::get('/submit-a-tip', [SiteController::class, 'tip'])->name('tip');
Route::post('/submit-a-tip', [FormController::class, 'tip'])->name('tip.store')->middleware('throttle:5,1');

Route::get('/about', [SiteController::class, 'page'])->defaults('page', 'about')->name('about');
Route::get('/editorial-policy', [SiteController::class, 'page'])->defaults('page', 'editorial-policy')->name('editorial');
Route::get('/corrections-policy', [SiteController::class, 'page'])->defaults('page', 'corrections')->name('corrections');
Route::get('/transparency', [SiteController::class, 'page'])->defaults('page', 'transparency')->name('transparency');
Route::get('/advertise', [SiteController::class, 'page'])->defaults('page', 'advertise')->name('advertise');
Route::get('/privacy-policy', [SiteController::class, 'page'])->defaults('page', 'privacy')->name('privacy');
Route::get('/terms-and-conditions', [SiteController::class, 'page'])->defaults('page', 'terms')->name('terms');

// ── Feeds & SEO ───────────────────────────────────────────────────────
Route::get('/sitemap.xml', [FeedController::class, 'sitemap'])->name('sitemap');
Route::get('/news-sitemap.xml', [FeedController::class, 'newsSitemap'])->name('sitemap.news');
Route::get('/feed.xml', [FeedController::class, 'rss'])->name('rss');
Route::get('/robots.txt', [FeedController::class, 'robots'])->name('robots');

// ── Admin ─────────────────────────────────────────────────────────────
Route::get('/admin/login', [AuthController::class, 'show'])->name('login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:8,1']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('articles', Admin\ArticleController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('authors', Admin\AuthorController::class)->except('show');
    Route::resource('ads', Admin\AdController::class)->except('show')->parameters(['ads' => 'advertisement']);
    Route::get('/media', [Admin\MediaController::class, 'index'])->name('media');
    Route::post('/media', [Admin\MediaController::class, 'store'])->name('media.store');
    Route::delete('/media', [Admin\MediaController::class, 'destroy'])->name('media.destroy');
    Route::get('/inbox', [Admin\InboxController::class, 'index'])->name('inbox');
});
