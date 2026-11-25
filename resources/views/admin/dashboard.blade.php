@extends('admin.layout')
@section('title', 'Dashboard')
@section('eyebrow', 'Overview')
@section('heading', 'Dashboard')
@section('actions')
    <a href="{{ route('admin.articles.create') }}" class="btn-gold !px-4 !py-2.5">+ New article</a>
@endsection

@section('content')
@php $max = max(1, $days->max('n')); @endphp
<div class="grid grid-cols-2 gap-px bg-line md:grid-cols-4 xl:grid-cols-7">
    @foreach ([['Total articles', $stats['total']], ['Published', $stats['published']], ['Drafts', $stats['drafts']], ['Scheduled', $stats['scheduled']], ['Authors', $stats['authors']], ['Active ads', $stats['ads']], ['Subscribers', $stats['subscribers']]] as [$l, $n])
        <div class="bg-ink-2 p-5"><p class="meta">{{ $l }}</p><p class="mt-3 font-display text-[40px] font-bold leading-none text-paper">{{ $n }}</p></div>
    @endforeach
</div>

<div class="mt-8 grid gap-8 xl:grid-cols-3">
    <section class="border border-line bg-card p-6 xl:col-span-2" aria-label="Publishing activity">
        <h2 class="kicker">Publishing activity · last 14 days</h2>
        <div class="mt-6 flex h-40 items-end gap-2" role="img" aria-label="Articles published per day">
            @foreach ($days as $d)
                <div class="group flex h-full flex-1 flex-col justify-end" title="{{ $d['label'] }}: {{ $d['n'] }}">
                    <div class="w-full {{ $d['n'] ? 'bg-gold' : 'bg-line-2' }} transition-colors group-hover:bg-paper" style="height: {{ $d['n'] ? max(8, $d['n'] / $max * 100) : 3 }}%"></div>
                </div>
            @endforeach
        </div>
        <div class="mt-2 flex justify-between font-mono text-[10px] text-mute"><span>{{ $days->first()['label'] }}</span><span>{{ $days->last()['label'] }}</span></div>
    </section>

    <section class="border border-line bg-card p-6" aria-label="Quick actions">
        <h2 class="kicker">Quick actions</h2>
        <div class="mt-5 grid gap-2">
            <a href="{{ route('admin.articles.create') }}" class="btn-line justify-between">Write an article <span>→</span></a>
            <a href="{{ route('admin.media') }}" class="btn-line justify-between">Upload images <span>→</span></a>
            <a href="{{ route('admin.ads.create') }}" class="btn-line justify-between">Add an advertisement <span>→</span></a>
            <a href="{{ route('admin.authors.create') }}" class="btn-line justify-between">Add an author <span>→</span></a>
        </div>
        @if ($upcoming->count())
            <h3 class="kicker mt-8">Scheduled next</h3>
            <ul class="mt-3 divide-y divide-line text-[14px]">@foreach ($upcoming as $u)<li class="py-2.5"><a href="{{ route('admin.articles.edit', $u) }}" class="hover:text-gold">{{ \Illuminate\Support\Str::limit($u->title, 48) }}</a><p class="meta mt-0.5">{{ $u->published_at->format('j M, H:i') }}</p></li>@endforeach</ul>
        @endif
    </section>
</div>

<section class="mt-8 border border-line bg-card" aria-label="Recent activity">
    <h2 class="kicker border-b border-line px-6 py-4">Recent activity</h2>
    <ul class="divide-y divide-line">
        @foreach ($recent as $a)
            <li class="flex flex-wrap items-center justify-between gap-3 px-6 py-3.5">
                <div class="min-w-0"><a href="{{ route('admin.articles.edit', $a) }}" class="block truncate text-[15px] hover:text-gold">{{ $a->title }}</a><p class="meta mt-0.5">{{ $a->author->name ?? 'No author' }} · {{ $a->category->name ?? 'Uncategorised' }} · updated {{ $a->updated_at->diffForHumans() }}</p></div>
                @include('admin.partials.status', ['article' => $a])
            </li>
        @endforeach
    </ul>
</section>
@endsection
