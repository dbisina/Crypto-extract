<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('partials.pagination');

        // Ticker data available to every public page via the main layout.
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $view->with('tickerSnapshot', app(\App\Services\MarketData::class)->snapshot());
        });
    }
}
