@extends('layouts.app')
@section('title', $author->name.', '.$author->role)
@section('description', \Illuminate\Support\Str::limit($author->bio, 155))
@section('og_type', 'profile')
@push('jsonld')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'Person', 'name' => $author->name, 'jobTitle' => $author->role, 'url' => route('author.show', $author), 'image' => $author->photo_url, 'description' => $author->bio, 'worksFor' => ['@type' => 'NewsMediaOrganization', 'name' => 'KryptoExtract'], 'sameAs' => array_values(array_filter([$author->twitter ? 'https://x.com/'.ltrim($author->twitter, '@') : null, $author->linkedin, $author->website]))], JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<header class="wrap pt-8 md:pt-12">
    <nav aria-label="Breadcrumb" class="meta flex gap-2"><a href="{{ route('home') }}" class="hover:text-gold">Home</a><span>/</span><span class="text-fog">Authors</span><span>/</span><span class="text-fog" aria-current="page">{{ $author->name }}</span></nav>
    <div class="mt-8 grid items-center gap-8 border-b border-line pb-12 md:grid-cols-[200px_1fr]">
        <img src="{{ $author->photo_url }}" alt="Portrait of {{ $author->name }}" class="size-40 rounded-full object-cover md:size-48" width="192" height="192">
        <div>
            <p class="kicker">{{ $author->role }}</p>
            <h1 class="hed mt-2 text-[44px] md:text-[72px]">{{ $author->name }}</h1>
            <p class="mt-4 max-w-2xl text-[17px] leading-relaxed text-fog">{{ $author->bio }}</p>
            <div class="mt-5 flex flex-wrap gap-5">
                @if ($author->twitter)<a href="https://x.com/{{ ltrim($author->twitter, '@') }}" rel="noopener me" class="meta hover:text-gold">X {{ $author->twitter }}</a>@endif
                @if ($author->linkedin)<a href="{{ $author->linkedin }}" rel="noopener me" class="meta hover:text-gold">LinkedIn</a>@endif
                @if ($author->website)<a href="{{ $author->website }}" rel="noopener me" class="meta hover:text-gold">Website</a>@endif
            </div>
        </div>
    </div>
</header>
<section class="wrap py-12">
    <h2 class="kicker">Stories by {{ $author->name }}</h2>
    <div class="mt-6 divide-y divide-line">
        @foreach ($articles as $a)<div class="py-7">@include('partials.story', ['article' => $a, 'variant' => 'row'])</div>@endforeach
    </div>
    {{ $articles->links() }}
</section>
@endsection
