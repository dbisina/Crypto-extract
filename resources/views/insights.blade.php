@extends('layouts.app')
@section('title', 'Insights: Opinion, Analysis & Long-form Reporting')
@section('description', 'Expert commentary, opinion, research and long-form reporting on digital assets and African finance.')

@section('content')
@include('partials.page-head', ['eyebrow' => 'Insights & analysis', 'title' => 'Insights', 'crumbs' => [['Insights']],
    'intro' => 'Opinion, research and long-form reporting. Our contributors take the time to explain what the headlines leave out.'])

<section class="wrap py-12">
    @php $first = $articles->first(); @endphp
    @if ($first && $articles->currentPage() === 1)
        <article class="group grid gap-8 border-b border-line pb-12 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <span class="font-display text-[100px] font-extrabold leading-[0.5] text-gold/60">“</span>
                <h2 class="hed -mt-2 text-[34px] md:text-[52px]"><a href="{{ $first->url }}" class="link-hed">{{ $first->title }}</a></h2>
                <p class="mt-5 max-w-xl text-[19px] leading-relaxed text-fog">{{ $first->excerpt }}</p>
                <div class="mt-7 flex items-center gap-3"><img src="{{ $first->author->photo_url }}" alt="" class="size-12 rounded-full object-cover" width="48" height="48"><div><p class="font-medium">{{ $first->author->name }}</p><p class="meta">{{ $first->author->role }} · {{ $first->reading_time }} min read</p></div></div>
            </div>
            <a href="{{ $first->url }}" class="lg:col-span-5"><div class="zoom aspect-[4/5]"><img src="{{ $first->img(900, 1100) }}" alt="{{ $first->image_alt }}"></div></a>
        </article>
    @endif
    <div class="mt-12 grid gap-px border-y border-line bg-line md:grid-cols-2">
        @foreach ($articles->skip($articles->currentPage() === 1 ? 1 : 0) as $a)
            <article class="group bg-ink p-7 md:p-9">
                <p class="kicker">{{ $a->country ?? 'Analysis' }}</p>
                <h3 class="hed mt-3 text-[26px] md:text-[30px]"><a href="{{ $a->url }}" class="link-hed">{{ $a->title }}</a></h3>
                <p class="mt-3 text-[15px] leading-relaxed text-fog">{{ $a->excerpt }}</p>
                <p class="meta mt-5">{{ $a->author->name }} · {{ $a->published_at?->format('j M Y') }}</p>
            </article>
        @endforeach
    </div>
    {{ $articles->links() }}
</section>
@endsection
