@extends('pages.shell')
@section('page_eyebrow', 'Newsroom')
@section('page_title', 'Submit a tip')
@section('page_intro', 'Know something we should be reporting? Tell us. You can stay anonymous.')
@section('page_body')
@if (session('status'))<div class="border border-up/50 bg-up/10 px-4 py-3 text-[15px] text-up" role="status">{{ session('status') }}</div>@endif
<p>We read every submission. Name and email are optional, but without a way to reach you we can't follow up. For highly sensitive material, avoid including identifying details in this form.</p>
<form method="post" action="{{ route('tip.store') }}" class="space-y-5 !mt-8">
    @csrf
    <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="meta" for="name">Name (optional)</label><input class="input mt-2" id="name" name="name" value="{{ old('name') }}"></div>
        <div><label class="meta" for="email">Email (optional)</label><input class="input mt-2" id="email" name="email" type="email" value="{{ old('email') }}"></div>
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="meta" for="subject">Subject</label><input class="input mt-2" id="subject" name="subject" value="{{ old('subject') }}" required>@error('subject')<p class="mt-1 text-sm text-down">{{ $message }}</p>@enderror</div>
        <div><label class="meta" for="country">Country (optional)</label><input class="input mt-2" id="country" name="country" value="{{ old('country') }}"></div>
    </div>
    <div><label class="meta" for="details">What should we know?</label><textarea class="input mt-2 min-h-48" id="details" name="details" required>{{ old('details') }}</textarea>@error('details')<p class="mt-1 text-sm text-down">{{ $message }}</p>@enderror</div>
    <button class="btn-gold">Submit tip</button>
</form>
@endsection
