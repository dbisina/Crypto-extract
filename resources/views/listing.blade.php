@extends('layouts.app')
@section('title', ($seoTitle ?? $title).($articles->currentPage() > 1 ? ' (Page '.$articles->currentPage().')' : ''))
@section('description', $intro ?? 'Latest coverage from KryptoExtract.')
@section('canonical', $articles->currentPage() > 1 ? $articles->url($articles->currentPage()) : url()->current())

@php
    $items = $articles->getCollection();
    $lead = ($withLead ?? false) && $articles->currentPage() === 1 ? $items->first() : null;
    $rest = $lead ? $items->slice(1) : $items;
@endphp

@section('content')
@include('partials.page-head', ['eyebrow' => $eyebrow ?? null, 'title' => $title, 'intro' => $intro ?? null, 'crumbs' => $crumbs ?? []])

@isset($snapshot)
    <div class="wrap mt-10"><div class="max-w-md">@include('partials.market-panel', ['snapshot' => $snapshot])</div></div>
@endisset

<section class="wrap py-12 md:py-16">
    @if ($lead)
        <div class="mb-12 border-b border-line pb-12">@include('partials.story', ['article' => $lead, 'variant' => 'wide'])</div>
    @endif

    @if ($rest->isEmpty() && ! $lead)
        <p class="py-16 text-center font-display text-2xl text-fog">No stories published here yet.</p>
    @else
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="divide-y divide-line lg:col-span-8">
                @foreach ($rest as $a)
                    <div class="py-7 first:pt-0">@include('partials.story', ['article' => $a, 'variant' => 'row'])</div>
                    @if ($loop->iteration === 4)
                        <div class="py-7"><x-ad placement="between_content" /></div>
                    @endif
                @endforeach
            </div>
            <aside class="space-y-10 lg:col-span-4 lg:border-l lg:border-line lg:pl-10">
                <x-ad placement="homepage_sidebar" class="mx-auto w-full max-w-[300px]" />
                <div>
                    <h2 class="kicker border-t-2 border-gold pt-3">Explore</h2>
                    <ul class="mt-4 divide-y divide-line text-[15px]">
                        @foreach (\App\Models\Category::orderBy('sort')->get() as $c)
                            <li><a href="{{ $c->url }}" class="flex items-center justify-between py-3 text-fog transition-colors hover:text-gold">{{ $c->name }}<span class="font-mono text-xs text-mute">→</span></a></li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    @endif

    {{ $articles->links() }}
</section>
@endsection
