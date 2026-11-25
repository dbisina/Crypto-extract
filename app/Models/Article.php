<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Article extends Model
{
    public const COUNTRIES = ['Nigeria', 'Ghana', 'Kenya', 'South Africa', 'Rwanda', 'Ethiopia', 'Egypt', 'Uganda', 'Tanzania'];

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'is_sponsored' => 'boolean',
        'is_press_release' => 'boolean',
        'is_demo' => 'boolean',
    ];

    public function author(): BelongsTo { return $this->belongsTo(Author::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function tags(): BelongsToMany { return $this->belongsToMany(Tag::class); }

    /** Live = published, or scheduled and its time has arrived. */
    public function scopeLive(Builder $q): Builder
    {
        return $q->whereIn('status', ['published', 'scheduled'])
            ->where('published_at', '<=', now());
    }

    public function scopeLatestFirst(Builder $q): Builder
    {
        return $q->orderByDesc('published_at');
    }

    public function scopeCountry(Builder $q, string $country): Builder
    {
        return $q->where('country', $country);
    }

    public function scopeInCategory(Builder $q, string $slug): Builder
    {
        return $q->whereHas('category', fn ($c) => $c->where('slug', $slug));
    }

    public function getRouteKeyName(): string { return 'slug'; }

    public function getUrlAttribute(): string { return route('article.show', $this); }

    public function getImageUrlAttribute(): ?string { return self::resolveImage($this->image); }

    public function getOgImageUrlAttribute(): ?string
    {
        return self::resolveImage($this->og_image) ?: $this->image_url;
    }

    public static function resolveImage(?string $path): ?string
    {
        if (! $path) return null;
        return Str::startsWith($path, ['http://', 'https://']) ? $path : asset('storage/'.$path);
    }

    /** Sized variant for Unsplash-hosted demo images; local uploads pass through. */
    public function img(int $w = 1200, ?int $h = null): ?string
    {
        $u = $this->image_url;
        if (! $u) {
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 10"><rect width="16" height="10" fill="#17191B"/><path d="M0 10 16 0" stroke="#26292C" stroke-width=".08"/><text x="8" y="6.6" font-family="Georgia,serif" font-size="4.6" font-weight="700" fill="#C9A44C" fill-opacity=".85" text-anchor="middle">K</text></svg>';
            return 'data:image/svg+xml;base64,'.base64_encode($svg);
        }
        if (Str::contains($u, 'images.unsplash.com')) {
            $u = preg_replace('/([?&])(w|h|fit|crop|q)=[^&]*/', '', $u);
            $u .= (Str::contains($u, '?') ? '&' : '?').'auto=format&fit=crop&q=75&w='.$w.($h ? '&h='.$h : '');
        }
        return $u;
    }

    public function getUpdatedLabelAttribute(): ?string
    {
        return $this->updated_at && $this->published_at && $this->updated_at->gt($this->published_at->copy()->addHour())
            ? $this->updated_at->format('j M Y') : null;
    }

    public function getLabelAttribute(): ?string
    {
        return match (true) {
            $this->is_sponsored => 'Sponsored',
            $this->is_press_release => 'Press release',
            $this->is_breaking => 'Breaking',
            default => null,
        };
    }

    public static function readingTime(?string $html): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $html)) / 220));
    }

    public function related(int $n = 4)
    {
        return static::live()->where('id', '!=', $this->id)
            ->where(fn ($q) => $q->where('category_id', $this->category_id)->when($this->country, fn ($q) => $q->orWhere('country', $this->country)))
            ->latestFirst()->with(['category', 'author'])->limit($n)->get();
    }
}
