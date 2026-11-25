@extends('admin.layout')
@section('title', 'Articles')
@section('eyebrow', 'Content')
@section('heading', 'Articles')
@section('actions')<a href="{{ route('admin.articles.create') }}" class="btn-gold !px-4 !py-2.5">+ New article</a>@endsection

@section('content')
<form method="get" class="mb-6 flex flex-wrap gap-3">
    <input name="q" value="{{ request('q') }}" placeholder="Search headlines…" class="input max-w-xs">
    <select name="status" class="input max-w-[180px]" onchange="this.form.submit()"><option value="">All statuses</option>@foreach (['published', 'scheduled', 'draft'] as $s)<option @selected(request('status') === $s)>{{ $s }}</option>@endforeach</select>
    <button class="btn-line !py-2.5">Filter</button>
</form>
<div class="overflow-x-auto border border-line bg-card">
    <table class="w-full min-w-[760px] text-left text-[14px]">
        <thead class="border-b border-line font-mono text-[10px] uppercase tracking-wider text-mute"><tr><th class="px-5 py-3">Headline</th><th class="px-3 py-3">Category</th><th class="px-3 py-3">Author</th><th class="px-3 py-3">Status</th><th class="px-3 py-3">Date</th><th class="px-5 py-3 text-right">Actions</th></tr></thead>
        <tbody class="divide-y divide-line">
            @forelse ($articles as $a)
                <tr class="hover:bg-raised/50">
                    <td class="max-w-md px-5 py-3.5"><a href="{{ route('admin.articles.edit', $a) }}" class="font-medium hover:text-gold">{{ \Illuminate\Support\Str::limit($a->title, 80) }}</a>
                        <div class="mt-1 flex gap-1.5">@if ($a->is_featured)<span class="badge border border-gold text-gold">Featured</span>@endif @if ($a->is_breaking)<span class="badge-breaking">Breaking</span>@endif @if ($a->is_sponsored)<span class="badge-sponsored">Sponsored</span>@endif @if ($a->is_press_release)<span class="badge-press">PR</span>@endif</div></td>
                    <td class="px-3 py-3.5 text-fog">{{ $a->category->name ?? '—' }}</td>
                    <td class="px-3 py-3.5 text-fog">{{ $a->author->name ?? '—' }}</td>
                    <td class="px-3 py-3.5">@include('admin.partials.status', ['article' => $a])</td>
                    <td class="px-3 py-3.5 font-mono text-[12px] text-mute">{{ $a->published_at?->format('j M Y') ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-right"><a href="{{ route('admin.articles.edit', $a) }}" class="meta hover:text-gold">Edit</a> · <a href="{{ $a->url }}" target="_blank" class="meta hover:text-gold">View</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-fog">No articles found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $articles->links() }}
@endsection
