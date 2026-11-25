@extends('pages.shell')
@section('page_eyebrow', 'Newsroom')
@section('page_title', 'Contact')
@section('page_intro', 'Questions, corrections, partnerships and press enquiries.')
@section('page_body')
@if (session('status'))<div class="border border-up/50 bg-up/10 px-4 py-3 text-[15px] text-up" role="status">{{ session('status') }}</div>@endif
<form method="post" action="{{ route('contact.store') }}" class="not-prose space-y-5 !mt-8">
    @csrf
    <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="meta" for="name">Name</label><input class="input mt-2" id="name" name="name" value="{{ old('name') }}" required>@error('name')<p class="mt-1 text-sm text-down">{{ $message }}</p>@enderror</div>
        <div><label class="meta" for="email">Email</label><input class="input mt-2" id="email" name="email" type="email" value="{{ old('email') }}" required>@error('email')<p class="mt-1 text-sm text-down">{{ $message }}</p>@enderror</div>
    </div>
    <div><label class="meta" for="topic">Topic</label>
        <select class="input mt-2" id="topic" name="topic">
            @foreach (['General', 'Correction', 'Advertising', 'Press', 'Privacy', 'Careers'] as $t)<option @selected(old('topic', request('topic')) === $t)>{{ $t }}</option>@endforeach
        </select></div>
    <div><label class="meta" for="message">Message</label><textarea class="input mt-2 min-h-40" id="message" name="message" required>{{ old('message') }}</textarea>@error('message')<p class="mt-1 text-sm text-down">{{ $message }}</p>@enderror</div>
    <button class="btn-gold">Send message</button>
</form>
<p class="!mt-10 text-[15px] text-fog">Have a story for us? Use the <a href="{{ route('tip') }}">tip form</a>, which can be anonymous.</p>
@endsection
