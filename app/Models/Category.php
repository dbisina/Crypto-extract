<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $guarded = [];

    public function articles(): HasMany { return $this->hasMany(Article::class); }

    public function getRouteKeyName(): string { return 'slug'; }

    public function getUrlAttribute(): string
    {
        return match ($this->slug) {
            'africa' => route('africa'),
            'markets' => route('markets'),
            'regulation' => route('regulation'),
            'stablecoins' => route('stablecoins'),
            'defi' => route('defi'),
            'insights' => route('insights'),
            default => route('category.show', $this),
        };
    }
}
