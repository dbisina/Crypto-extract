<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index()
    {
        return view('admin.authors.index', ['authors' => Author::withCount('articles')->orderBy('name')->get()]);
    }

    public function create() { return view('admin.authors.form', ['author' => new Author()]); }

    public function store(Request $r)
    {
        Author::create($this->data($r));
        return redirect()->route('admin.authors.index')->with('status', 'Author added.');
    }

    public function edit(Author $author) { return view('admin.authors.form', compact('author')); }

    public function update(Request $r, Author $author)
    {
        $author->update($this->data($r, $author));
        return redirect()->route('admin.authors.index')->with('status', 'Author updated.');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('admin.authors.index')->with('status', 'Author deleted. Their articles are now unassigned.');
    }

    private function data(Request $r, ?Author $a = null): array
    {
        $d = $r->validate([
            'name' => 'required|string|max:120',
            'slug' => ['nullable', 'regex:/^[a-z0-9\-]+$/', Rule::unique('authors', 'slug')->ignore($a?->id)],
            'role' => 'nullable|string|max:120', 'bio' => 'nullable|string|max:2000',
            'twitter' => 'nullable|string|max:80', 'linkedin' => 'nullable|url|max:200', 'website' => 'nullable|url|max:200',
            'photo_file' => 'nullable|image|max:4096',
        ]);
        $d['slug'] = $d['slug'] ?: Str::slug($d['name']);
        if ($r->hasFile('photo_file')) $d['photo'] = $r->file('photo_file')->store('uploads', 'public');
        unset($d['photo_file']);
        return $d;
    }
}
