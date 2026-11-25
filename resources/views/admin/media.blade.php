@extends('admin.layout')
@section('title', 'Media library')
@section('eyebrow', 'Assets')
@section('heading', 'Media library')
@section('content')
<form method="post" enctype="multipart/form-data" action="{{ route('admin.media.store') }}" class="flex flex-wrap items-center gap-3 border border-dashed border-line-2 bg-card p-5">
    @csrf
    <input type="file" name="files[]" multiple accept="image/*" required class="input max-w-md !p-2 text-[13px]">
    <button class="btn-gold !py-2.5">Upload images</button>
    <span class="text-[12px] text-mute">JPG, PNG, WebP or GIF · up to 8 MB each</span>
</form>
<div x-data="{ copied: null }" class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
    @forelse ($files as $f)
        <figure class="group border border-line bg-card">
            <div class="aspect-square overflow-hidden bg-raised"><img src="{{ $f['url'] }}" alt="" loading="lazy" class="size-full object-cover"></div>
            <figcaption class="space-y-2 p-3">
                <p class="truncate font-mono text-[11px] text-fog" title="{{ $f['name'] }}">{{ $f['name'] }}</p>
                <p class="font-mono text-[10px] text-mute">{{ number_format($f['size'] / 1024) }} KB</p>
                <div class="flex justify-between">
                    <button type="button" @click="navigator.clipboard.writeText(@js($f['url'])); copied = @js($f['path']); setTimeout(() => copied = null, 1500)" class="meta hover:text-gold" x-text="copied === @js($f['path']) ? 'Copied ✓' : 'Copy URL'">Copy URL</button>
                    <form method="post" action="{{ route('admin.media.destroy') }}" onsubmit="return confirm('Delete this file? Articles using it will lose the image.')">@csrf @method('DELETE')<input type="hidden" name="path" value="{{ $f['path'] }}"><button class="meta hover:text-down">Delete</button></form>
                </div>
            </figcaption>
        </figure>
    @empty
        <p class="col-span-full py-16 text-center text-fog">No uploads yet. Add images above, then pick them when editing an article.</p>
    @endforelse
</div>
@endsection
