@extends('layouts.app')
@section('title', 'Africa in Focus: Crypto & Digital Finance News from the Continent')
@section('description', 'Cryptocurrency, stablecoin and regulatory news from Nigeria, Ghana, Kenya, South Africa, Rwanda and across Africa.')

@section('content')
@include('partials.page-head', ['eyebrow' => 'Our core beat', 'title' => 'Africa in focus', 'crumbs' => [['Africa']],
    'intro' => 'The continent where digital assets are most used for real payments. Reported from the ground by our Lagos, Accra, Nairobi and Johannesburg desks.'])

<section class="wrap py-10" aria-label="Countries">
    <div class="grid grid-cols-2 gap-px bg-line sm:grid-cols-3 lg:grid-cols-6">
        @foreach (['Nigeria', 'Ghana', 'Kenya', 'South Africa', 'Rwanda', 'Ethiopia'] as $c)
            <a href="{{ route('country', \Illuminate\Support\Str::slug($c)) }}" class="group bg-ink-2 p-5 transition-colors hover:bg-card">
                <p class="font-display text-[22px] font-bold italic text-paper transition-colors group-hover:text-gold">{{ $c }}</p>
                <p class="meta mt-6">{{ $counts[$c] ?? 0 }} {{ \Illuminate\Support\Str::plural('story', $counts[$c] ?? 0) }} →</p>
            </a>
        @endforeach
    </div>
</section>

<section class="wrap pb-16">
    @if ($lead && $rest->currentPage() === 1)
        <div class="border-b border-line pb-12">@include('partials.story', ['article' => $lead, 'variant' => 'wide'])</div>
    @endif
    <div class="mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($rest as $i => $a)
            @include('partials.story', ['article' => $a, 'variant' => 'card', 'ratio' => 'aspect-[3/2]', 'size' => 'text-[21px]'])
        @endforeach
    </div>
    {{ $rest->links() }}
</section>
@endsection
