@extends('admin.layout')
@php $isNew = ! $ad->exists; @endphp
@section('title', $isNew ? 'New advertisement' : 'Edit advertisement')
@section('eyebrow', 'Advertising')
@section('heading', $isNew ? 'New advertisement' : 'Edit advertisement')
@section('actions')<a href="{{ route('admin.ads.index') }}" class="btn-line !px-4 !py-2.5">← Back</a>@endsection
@section('content')
<form method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.ads.store') : route('admin.ads.update', $ad) }}" class="max-w-2xl space-y-5 border border-line bg-card p-6">
    @csrf @unless ($isNew) @method('PUT') @endunless
    <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="meta" for="name">Campaign name</label><input id="name" name="name" value="{{ old('name', $ad->name) }}" required class="input mt-2"></div>
        <div><label class="meta" for="advertiser">Advertiser</label><input id="advertiser" name="advertiser" value="{{ old('advertiser', $ad->advertiser) }}" required class="input mt-2"></div>
    </div>
    <div><label class="meta" for="placement">Placement</label>
        <select id="placement" name="placement" class="input mt-2">@foreach (\App\Models\Advertisement::PLACEMENTS as $k => $l)<option value="{{ $k }}" @selected(old('placement', $ad->placement) === $k)>{{ $l }}</option>@endforeach</select></div>
    <div><label class="meta" for="url">Destination URL</label><input id="url" name="url" type="url" value="{{ old('url', $ad->url) }}" required class="input mt-2" placeholder="https://"></div>
    <div class="flex items-center gap-4">@if ($ad->banner_url)<img src="{{ $ad->banner_url }}" alt="" class="h-16 max-w-48 object-contain">@endif
        <div class="grow"><label class="meta" for="banner_file">Banner image</label><input id="banner_file" name="banner_file" type="file" accept="image/*" class="input mt-2 !p-2 text-[13px]"></div></div>
    <div><label class="meta" for="headline">Text creative headline <span class="normal-case tracking-normal text-mute">(used when no banner is uploaded)</span></label><input id="headline" name="headline" value="{{ old('headline', $ad->headline) }}" class="input mt-2"></div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="meta" for="starts_at">Start date</label><input id="starts_at" name="starts_at" type="date" value="{{ old('starts_at', $ad->starts_at?->format('Y-m-d')) }}" class="input mt-2"></div>
        <div><label class="meta" for="ends_at">End date</label><input id="ends_at" name="ends_at" type="date" value="{{ old('ends_at', $ad->ends_at?->format('Y-m-d')) }}" class="input mt-2"></div>
    </div>
    <label class="flex items-center gap-2.5 text-[14px]"><input type="checkbox" name="active" value="1" @checked(old('active', $ad->active)) class="accent-[#C9A44C]"> Enabled</label>
    <button class="btn-gold">{{ $isNew ? 'Create advertisement' : 'Save changes' }}</button>
</form>
@endsection
