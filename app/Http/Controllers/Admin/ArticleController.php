<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $r)
    {
        $articles = Article::with(['category', 'author'])
            ->when($r->status, fn ($q, $s) => $q->where('status', $s))
            ->when($r->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderByDesc('updated_at')->paginate(20)->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.form', $this->formData(new Article(['status' => 'draft', 'kind' => 'news'])));
    }

    public function store(Request $r)
    {
        $article = new Article();
        $this->save($article, $r);
        return redirect()->route('admin.articles.edit', $article)->with('status', 'Article saved.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', $this->formData($article));
    }

    public function update(Request $r, Article $article)
    {
        $this->save($article, $r);
        return redirect()->route('admin.articles.edit', $article)->with('status', 'Article updated.');
    }

    public function destroy(Article $article)
    {
        $article->tags()->detach();
        $article->delete();
        return redirect()->route('admin.articles.index')->with('status', 'Article deleted.');
    }

    private function formData(Article $article): array
    {
        return [
            'article' => $article,
            'categories' => Category::orderBy('sort')->get(),
            'authors' => Author::orderBy('name')->get(),
            'media' => $this->mediaList(),
            'tagString' => $article->exists ? $article->tags->pluck('name')->implode(', ') : '',
        ];
    }

    private function mediaList(): array
    {
        return collect(Storage::disk('public')->files('uploads'))
            ->reject(fn ($f) => str_starts_with(basename($f), '.'))
            ->map(fn ($f) => ['path' => $f, 'url' => asset('storage/'.$f)])->values()->all();
    }

    private function save(Article $article, Request $r): void
    {
        $data = $r->validate([
            'title' => 'required|string|max:200',
            'slug' => ['nullable', 'string', 'max:200', 'regex:/^[a-z0-9\-]+$/', Rule::unique('articles', 'slug')->ignore($article->id)],
            'excerpt' => 'nullable|string|max:600',
            'body' => 'nullable|string',
            'kind' => 'required|in:news,insight,guide',
            'author_id' => 'nullable|exists:authors,id',
            'category_id' => 'nullable|exists:categories,id',
            'country' => 'nullable|string|max:60',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'image_alt' => 'nullable|string|max:200',
            'image_caption' => 'nullable|string|max:300',
            'image_path' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:6144',
            'og_file' => 'nullable|image|max:6144',
            'seo_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:170',
            'sponsor_name' => 'nullable|string|max:120',
            'sources' => 'nullable|string|max:3000',
            'tags' => 'nullable|string|max:300',
        ]);

        $slug = $data['slug'] ?: Str::slug(Str::limit($data['title'], 70, ''));
        $base = $slug; $i = 2;
        while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) { $slug = $base.'-'.$i++; }

        $body = preg_replace('/<(\/?)h1\b/i', '<$1h2', (string) ($data['body'] ?? ''));
        $body = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $body);
        // Trix emits <div> for paragraphs and bare text inside quotes; normalise to semantic HTML.
        $body = preg_replace('/<div>(?:\s|<br>)*<\/div>/i', '', $body);
        $body = preg_replace('/<div>(.*?)<\/div>/is', '<p>$1</p>', $body);
        $body = preg_replace('/<blockquote>(?!\s*<p>)(.*?)<\/blockquote>/is', '<blockquote><p>$1</p></blockquote>', $body);

        $publishedAt = $data['published_at'] ? \Illuminate\Support\Carbon::parse($data['published_at']) : null;
        if ($data['status'] === 'published' && ! $publishedAt) $publishedAt = now();
        if ($data['status'] === 'scheduled' && ! $publishedAt) $publishedAt = now()->addDay();

        $image = $article->image;
        if ($r->hasFile('image_file')) $image = $r->file('image_file')->store('uploads', 'public');
        elseif (! empty($data['image_path'])) $image = $data['image_path'];

        $og = $r->hasFile('og_file') ? $r->file('og_file')->store('uploads', 'public') : $article->og_image;

        $article->fill([
            'title' => $data['title'], 'slug' => $slug, 'excerpt' => $data['excerpt'] ?? null, 'body' => $body,
            'kind' => $data['kind'], 'author_id' => $data['author_id'] ?? null, 'category_id' => $data['category_id'] ?? null,
            'country' => $data['country'] ?: null, 'status' => $data['status'], 'published_at' => $publishedAt,
            'image' => $image, 'image_alt' => $data['image_alt'] ?? null, 'image_caption' => $data['image_caption'] ?? null,
            'og_image' => $og, 'seo_title' => $data['seo_title'] ?? null, 'meta_description' => $data['meta_description'] ?? null,
            'sources' => $data['sources'] ?? null, 'sponsor_name' => $data['sponsor_name'] ?? null,
            'is_featured' => $r->boolean('is_featured'), 'is_breaking' => $r->boolean('is_breaking'),
            'is_sponsored' => $r->boolean('is_sponsored'), 'is_press_release' => $r->boolean('is_press_release'),
            'reading_time' => Article::readingTime($body),
            'is_demo' => $article->exists ? $article->is_demo : false,
        ])->save();

        // Only one featured story at a time.
        if ($article->is_featured) Article::where('id', '!=', $article->id)->update(['is_featured' => false]);

        $ids = collect(explode(',', (string) ($data['tags'] ?? '')))->map(fn ($t) => trim($t))->filter()->unique()
            ->map(fn ($t) => Tag::firstOrCreate(['slug' => Str::slug($t)], ['name' => $t])->id);
        $article->tags()->sync($ids);
    }
}
