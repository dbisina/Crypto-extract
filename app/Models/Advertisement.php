<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Advertisement extends Model
{
    public const PLACEMENTS = [
        'header_leaderboard' => 'Header leaderboard (728×90)',
        'homepage_banner' => 'Homepage banner (970×250)',
        'homepage_sidebar' => 'Homepage sidebar (300×250)',
        'in_article' => 'In-article (728×90)',
        'between_content' => 'Between-content (970×120)',
        'footer' => 'Footer (728×90)',
        'newsletter_sponsor' => 'Newsletter sponsorship',
    ];

    protected $guarded = [];

    protected $casts = ['starts_at' => 'date', 'ends_at' => 'date', 'active' => 'boolean'];

    public function scopeRunning(Builder $q): Builder
    {
        $today = now()->toDateString();
        return $q->where('active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $today))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $today));
    }

    public static function forPlacement(string $placement): ?self
    {
        return static::running()->where('placement', $placement)->inRandomOrder()->first();
    }

    public function getBannerUrlAttribute(): ?string
    {
        if (! $this->banner) return null;
        return Str::startsWith($this->banner, ['http://', 'https://']) ? $this->banner : asset('storage/'.$this->banner);
    }
}
