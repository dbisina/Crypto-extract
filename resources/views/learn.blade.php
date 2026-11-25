@extends('layouts.app')
@section('title', 'Learn: Guides to Crypto, Stablecoins & Digital Assets')
@section('description', 'Plain-language guides to bitcoin, stablecoins, self-custody and digital-asset regulation, written for newcomers and professionals.')

@section('content')
@include('partials.page-head', ['eyebrow' => 'Guides', 'title' => 'Learn', 'crumbs' => [['Learn']],
    'intro' => 'Plain-language explainers on how digital assets work, what can go wrong, and how to read the numbers.'])

<section class="wrap py-12 md:py-16">
    <div class="grid gap-px bg-line md:grid-cols-3">
        @forelse ($articles as $i => $a)
            <article class="group flex flex-col bg-ink-2 p-7 transition-colors hover:bg-card">
                <span class="font-mono text-[11px] tracking-wider text-gold">GUIDE {{ str_pad($articles->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</span>
                <h2 class="hed mt-5 flex-1 text-[26px]"><a href="{{ $a->url }}" class="link-hed">{{ preg_replace('/^Guide:\s*/', '', $a->title) }}</a></h2>
                <p class="mt-4 text-[15px] leading-relaxed text-fog">{{ $a->excerpt }}</p>
                <p class="meta mt-6 border-t border-line pt-4">{{ $a->reading_time }} min read · {{ $a->category->name ?? '' }}</p>
            </article>
        @empty
            <p class="bg-ink py-16 text-center font-display text-2xl text-fog md:col-span-3">Guides are coming soon.</p>
        @endforelse
    </div>
    <p class="mx-auto mt-10 max-w-2xl text-center text-[13px] leading-relaxed text-mute">Educational content only. Nothing here is investment, legal or tax advice. Digital assets are volatile and you can lose all of your money.</p>
    {{ $articles->links() }}
</section>
@endsection
