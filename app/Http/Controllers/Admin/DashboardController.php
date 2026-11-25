<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Author;
use App\Models\Subscriber;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'drafts' => Article::where('status', 'draft')->count(),
            'scheduled' => Article::where('status', 'scheduled')->where('published_at', '>', now())->count(),
            'authors' => Author::count(),
            'ads' => Advertisement::running()->count(),
            'subscribers' => Subscriber::count(),
        ];

        // Articles published per day, last 14 days.
        $days = collect(range(13, 0))->map(function ($d) {
            $day = now()->subDays($d)->startOfDay();
            return ['label' => $day->format('j M'), 'n' => Article::whereIn('status', ['published', 'scheduled'])
                ->whereBetween('published_at', [$day, $day->copy()->endOfDay()])->count()];
        });

        return view('admin.dashboard', [
            'stats' => $stats, 'days' => $days,
            'recent' => Article::with(['author', 'category'])->orderByDesc('updated_at')->limit(8)->get(),
            'upcoming' => Article::where('status', 'scheduled')->where('published_at', '>', now())->orderBy('published_at')->limit(5)->get(),
        ]);
    }
}
