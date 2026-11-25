@extends('admin.layout')
@section('title', 'Categories')
@section('eyebrow', 'Taxonomy')
@section('heading', 'Categories')
@section('actions')<a href="{{ route('admin.categories.create') }}" class="btn-gold !px-4 !py-2.5">+ New category</a>@endsection
@section('content')
<div class="overflow-x-auto border border-line bg-card">
    <table class="w-full min-w-[560px] text-left text-[14px]">
        <thead class="border-b border-line font-mono text-[10px] uppercase tracking-wider text-mute"><tr><th class="px-5 py-3">Name</th><th class="px-3 py-3">Slug</th><th class="px-3 py-3">Articles</th><th class="px-5 py-3 text-right">Actions</th></tr></thead>
        <tbody class="divide-y divide-line">
            @foreach ($categories as $c)
                <tr><td class="px-5 py-3.5 font-medium">{{ $c->name }}</td><td class="px-3 py-3.5 font-mono text-[12px] text-mute">{{ $c->slug }}</td><td class="px-3 py-3.5 text-fog">{{ $c->articles_count }}</td>
                    <td class="px-5 py-3.5 text-right"><a href="{{ route('admin.categories.edit', $c) }}" class="meta hover:text-gold">Edit</a> ·
                        <form method="post" action="{{ route('admin.categories.destroy', $c) }}" class="inline" onsubmit="return confirm('Delete this category? Its articles will become uncategorised.')">@csrf @method('DELETE')<button class="meta hover:text-down">Delete</button></form></td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
