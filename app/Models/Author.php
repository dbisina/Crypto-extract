<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Author extends Model
{
    protected $guarded = [];

    public function articles(): HasMany { return $this->hasMany(Article::class); }

    public function getRouteKeyName(): string { return 'slug'; }

    public function getPhotoUrlAttribute(): string
    {
        if (! $this->photo) {
            return 'https://ui-avatars.com/api/?background=202326&color=C9A44C&bold=true&size=256&name='.urlencode($this->name);
        }
        return Str::startsWith($this->photo, ['http://', 'https://']) ? $this->photo : asset('storage/'.$this->photo);
    }
}
