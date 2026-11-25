@extends('admin.layout')
@php $isNew = ! $category->exists; @endphp
@section('title', $isNew ? 'New category' : 'Edit category')
@section('eyebrow', 'Categories')
@section('heading', $isNew ? 'New category' : 'Edit category')
@section('actions')<a href="{{ route('admin.categories.index') }}" class="btn-line !px-4 !py-2.5">← Back</a>@endsection
@section('content')
<form method="post" action="{{ $isNew ? route('admin.categories.store') : route('admin.categories.update', $category) }}" class="max-w-xl space-y-5 border border-line bg-card p-6">
    @csrf @unless ($isNew) @method('PUT') @endunless
    <div><label class="meta" for="name">Name</label><input id="name" name="name" value="{{ old('name', $category->name) }}" required class="input mt-2"></div>
    <div><label class="meta" for="slug">Slug</label><input id="slug" name="slug" value="{{ old('slug', $category->slug) }}" class="input mt-2" placeholder="auto-generated"></div>
    <div><label class="meta" for="description">Description</label><textarea id="description" name="description" rows="3" class="input mt-2">{{ old('description', $category->description) }}</textarea></div>
    <div><label class="meta" for="sort">Sort order</label><input id="sort" name="sort" type="number" value="{{ old('sort', $category->sort ?? 99) }}" class="input mt-2 max-w-[120px]"></div>
    <button class="btn-gold">{{ $isNew ? 'Create category' : 'Save changes' }}</button>
</form>
@endsection
