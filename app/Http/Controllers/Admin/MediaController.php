<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('public');
        $files = collect($disk->allFiles('uploads'))
            ->reject(fn ($f) => str_starts_with(basename($f), '.'))
            ->map(fn ($f) => ['path' => $f, 'url' => asset('storage/'.$f), 'name' => basename($f), 'size' => $disk->size($f), 'time' => $disk->lastModified($f)])
            ->sortByDesc('time')->values();
        return view('admin.media', compact('files'));
    }

    public function store(Request $r)
    {
        $r->validate(['files' => 'required|array|max:20', 'files.*' => 'image|max:8192']);
        foreach ($r->file('files') as $f) $f->store('uploads', 'public');
        return back()->with('status', count($r->file('files')).' file(s) uploaded.');
    }

    public function destroy(Request $r)
    {
        $path = (string) $r->input('path');
        abort_unless(str_starts_with($path, 'uploads/') && ! str_contains($path, '..'), 422);
        Storage::disk('public')->delete($path);
        return back()->with('status', 'File deleted.');
    }
}
