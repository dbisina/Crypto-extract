@extends('admin.layout')
@section('title', 'Authors')
@section('eyebrow', 'People')
@section('heading', 'Authors')
@section('actions')<a href="{{ route('admin.authors.create') }}" class="btn-gold !px-4 !py-2.5">+ New author</a>@endsection
@section('content')
<div class="grid gap-px bg-line sm:grid-cols-2 xl:grid-cols-3">
    @foreach ($authors as $a)
        <div class="flex gap-4 bg-ink-2 p-5">
            <img src="{{ $a->photo_url }}" alt="" class="size-14 shrink-0 rounded-full object-cover" width="56" height="56">
            <div class="min-w-0"><p class="font-medium">{{ $a->name }}</p><p class="meta">{{ $a->role }}</p><p class="mt-1 text-[13px] text-mute">{{ $a->articles_count }} articles</p>
                <div class="mt-3 flex gap-3"><a href="{{ route('admin.authors.edit', $a) }}" class="meta hover:text-gold">Edit</a><a href="{{ route('author.show', $a) }}" target="_blank" class="meta hover:text-gold">Profile ↗</a>
                    <form method="post" action="{{ route('admin.authors.destroy', $a) }}" onsubmit="return confirm('Delete this author? Their articles will be unassigned.')">@csrf @method('DELETE')<button class="meta hover:text-down">Delete</button></form></div></div>
        </div>
    @endforeach
</div>
@endsection
