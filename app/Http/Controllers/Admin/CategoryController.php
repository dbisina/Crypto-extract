<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', ['categories' => Category::withCount('articles')->orderBy('sort')->get()]);
    }

    public function create() { return view('admin.categories.form', ['category' => new Category()]); }

    public function store(Request $r)
    {
        Category::create($this->data($r));
        return redirect()->route('admin.categories.index')->with('status', 'Category created.');
    }

    public function edit(Category $category) { return view('admin.categories.form', compact('category')); }

    public function update(Request $r, Category $category)
    {
        $category->update($this->data($r, $category));
        return redirect()->route('admin.categories.index')->with('status', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete(); // articles keep existing with category_id = null
        return redirect()->route('admin.categories.index')->with('status', 'Category deleted.');
    }

    private function data(Request $r, ?Category $c = null): array
    {
        $d = $r->validate([
            'name' => 'required|string|max:80',
            'slug' => ['nullable', 'regex:/^[a-z0-9\-]+$/', Rule::unique('categories', 'slug')->ignore($c?->id)],
            'description' => 'nullable|string|max:300',
            'sort' => 'nullable|integer|min:0|max:999',
        ]);
        $d['slug'] = $d['slug'] ?: Str::slug($d['name']);
        $d['sort'] = $d['sort'] ?? 99;
        return $d;
    }
}
