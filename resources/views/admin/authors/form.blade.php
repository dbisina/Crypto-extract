@extends('admin.layout')
@php $isNew = ! $author->exists; @endphp
@section('title', $isNew ? 'New author' : 'Edit author')
@section('eyebrow', 'Authors')
@section('heading', $isNew ? 'New author' : 'Edit author')
@section('actions')<a href="{{ route('admin.authors.index') }}" class="btn-line !px-4 !py-2.5">← Back</a>@endsection
@section('content')
<form method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.authors.store') : route('admin.authors.update', $author) }}" class="max-w-2xl space-y-5 border border-line bg-card p-6">
    @csrf @unless ($isNew) @method('PUT') @endunless
    <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="meta" for="name">Name</label><input id="name" name="name" value="{{ old('name', $author->name) }}" required class="input mt-2"></div>
        <div><label class="meta" for="role">Role / title</label><input id="role" name="role" value="{{ old('role', $author->role) }}" class="input mt-2"></div>
    </div>
    <div><label class="meta" for="slug">Slug</label><input id="slug" name="slug" value="{{ old('slug', $author->slug) }}" class="input mt-2" placeholder="auto-generated"></div>
    <div><label class="meta" for="bio">Biography</label><textarea id="bio" name="bio" rows="5" class="input mt-2">{{ old('bio', $author->bio) }}</textarea></div>
    <div class="flex items-center gap-4"><img src="{{ $author->photo_url }}" alt="" class="size-16 rounded-full object-cover" width="64" height="64"><div class="grow"><label class="meta" for="photo_file">Photograph</label><input id="photo_file" name="photo_file" type="file" accept="image/*" class="input mt-2 !p-2 text-[13px]"></div></div>
    <div class="grid gap-5 sm:grid-cols-3">
        <div><label class="meta" for="twitter">X / Twitter</label><input id="twitter" name="twitter" value="{{ old('twitter', $author->twitter) }}" class="input mt-2" placeholder="@handle"></div>
        <div><label class="meta" for="linkedin">LinkedIn URL</label><input id="linkedin" name="linkedin" value="{{ old('linkedin', $author->linkedin) }}" class="input mt-2"></div>
        <div><label class="meta" for="website">Website</label><input id="website" name="website" value="{{ old('website', $author->website) }}" class="input mt-2"></div>
    </div>
    <button class="btn-gold">{{ $isNew ? 'Add author' : 'Save changes' }}</button>
</form>
@endsection
