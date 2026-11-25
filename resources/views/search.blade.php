@extends('layouts.app')
@section('title', $q !== '' ? 'Search: '.$q : 'Search')
@section('noindex', '1')

@section('content')
<header class="wrap pt-8 md:pt-12">
    <p class="kicker">Search</p>
    <form action="{{ route('search') }}" method="get" class="mt-4 flex items-end border-b-2 border-gold">
        <input name="q" type="search" value="{{ $q }}" placeholder="Search KryptoExtract…" class="w-full bg-transparent py-3 font-display text-[34px] text-paper placeholder:text-mute focus:outline-none md:text-[56px]" aria-label="Search query">
        <button class="btn-gold mb-3 ml-3 shrink-0">Search</button>
    </form>
    @if ($articles)<p class="meta mt-4">{{ $articles->total() }} {{ \Illuminate\Support\Str::plural('result', $articles->total()) }} for “{{ $q }}”</p>@endif
</header>
<section class="wrap py-12">
    @if ($articles === null)
        <p class="font-display text-2xl text-fog">Type a topic, country or asset to search the archive.</p>
    @elseif ($articles->isEmpty())
        <p class="font-display text-2xl text-fog">No stories matched “{{ $q }}”. Try a broader term.</p>
    @else
        <div class="divide-y divide-line">@foreach ($articles as $a)<div class="py-7">@include('partials.story', ['article' => $a, 'variant' => 'row'])</div>@endforeach</div>
        {{ $articles->links() }}
    @endif
</section>
@endsection
