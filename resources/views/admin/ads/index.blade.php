@extends('admin.layout')
@section('title', 'Advertising')
@section('eyebrow', 'Revenue')
@section('heading', 'Advertising')
@section('actions')<a href="{{ route('admin.ads.create') }}" class="btn-gold !px-4 !py-2.5">+ New advertisement</a>@endsection
@section('content')
<div class="overflow-x-auto border border-line bg-card">
    <table class="w-full min-w-[820px] text-left text-[14px]">
        <thead class="border-b border-line font-mono text-[10px] uppercase tracking-wider text-mute"><tr><th class="px-5 py-3">Campaign</th><th class="px-3 py-3">Placement</th><th class="px-3 py-3">Flight</th><th class="px-3 py-3">Clicks</th><th class="px-3 py-3">Status</th><th class="px-5 py-3 text-right">Actions</th></tr></thead>
        <tbody class="divide-y divide-line">
            @forelse ($ads as $ad)
                @php $running = \App\Models\Advertisement::running()->whereKey($ad->id)->exists(); @endphp
                <tr>
                    <td class="px-5 py-3.5"><p class="font-medium">{{ $ad->name }}</p><p class="meta">{{ $ad->advertiser }}</p></td>
                    <td class="px-3 py-3.5 text-fog">{{ \App\Models\Advertisement::PLACEMENTS[$ad->placement] ?? $ad->placement }}</td>
                    <td class="px-3 py-3.5 font-mono text-[12px] text-mute">{{ $ad->starts_at?->format('j M') ?? '—' }} → {{ $ad->ends_at?->format('j M Y') ?? 'open' }}</td>
                    <td class="px-3 py-3.5 font-mono text-fog">{{ number_format($ad->clicks) }}</td>
                    <td class="px-3 py-3.5"><span class="inline-block border px-2 py-[3px] font-mono text-[10px] uppercase tracking-wider {{ $running ? 'border-up/60 text-up' : 'border-line-2 text-fog' }}">{{ $running ? 'Live' : ($ad->active ? 'Not in flight' : 'Disabled') }}</span></td>
                    <td class="px-5 py-3.5 text-right"><a href="{{ route('admin.ads.edit', $ad) }}" class="meta hover:text-gold">Edit</a> ·
                        <form method="post" action="{{ route('admin.ads.destroy', $ad) }}" class="inline" onsubmit="return confirm('Delete this advertisement?')">@csrf @method('DELETE')<button class="meta hover:text-down">Delete</button></form></td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-fog">No advertisements yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<p class="mt-4 text-[13px] text-mute">If several live ads share a placement they rotate randomly. A placement with no live ad collapses cleanly; no empty boxes are shown to readers.</p>
@endsection
