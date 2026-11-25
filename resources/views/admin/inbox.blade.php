@extends('admin.layout')
@section('title', 'Inbox')
@section('eyebrow', 'Audience')
@section('heading', 'Inbox')
@section('content')
<div class="grid gap-8 xl:grid-cols-3">
    <section class="border border-line bg-card"><h2 class="kicker border-b border-line px-5 py-4">Subscribers · {{ $subscriberCount }}</h2>
        <ul class="max-h-[480px] divide-y divide-line overflow-auto">@forelse ($subscribers as $s)<li class="flex justify-between gap-3 px-5 py-2.5 text-[13px]"><span class="truncate">{{ $s->email }}</span><span class="shrink-0 font-mono text-[11px] text-mute">{{ $s->created_at->format('j M') }}</span></li>@empty<li class="px-5 py-8 text-center text-fog">No subscribers yet.</li>@endforelse</ul></section>
    <section class="border border-line bg-card"><h2 class="kicker border-b border-line px-5 py-4">Contact messages · {{ $contacts->count() }}</h2>
        <ul class="max-h-[480px] divide-y divide-line overflow-auto">@forelse ($contacts as $c)<li class="px-5 py-3.5 text-[13px]"><p class="font-medium">{{ $c->name }} <span class="font-normal text-mute">· {{ $c->topic }}</span></p><a href="mailto:{{ $c->email }}" class="text-gold">{{ $c->email }}</a><p class="mt-1 text-fog">{{ $c->message }}</p><p class="meta mt-1">{{ $c->created_at->diffForHumans() }}</p></li>@empty<li class="px-5 py-8 text-center text-fog">No messages.</li>@endforelse</ul></section>
    <section class="border border-line bg-card"><h2 class="kicker border-b border-line px-5 py-4">Tips · {{ $tips->count() }}</h2>
        <ul class="max-h-[480px] divide-y divide-line overflow-auto">@forelse ($tips as $t)<li class="px-5 py-3.5 text-[13px]"><p class="font-medium">{{ $t->subject }} @if ($t->country)<span class="font-normal text-mute">· {{ $t->country }}</span>@endif</p><p class="meta">{{ $t->name ?: 'Anonymous' }} {{ $t->email ? '· '.$t->email : '' }}</p><p class="mt-1 text-fog">{{ $t->details }}</p><p class="meta mt-1">{{ $t->created_at->diffForHumans() }}</p></li>@empty<li class="px-5 py-8 text-center text-fog">No tips.</li>@endforelse</ul></section>
</div>
@endsection
