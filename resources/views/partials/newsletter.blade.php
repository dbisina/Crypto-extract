@props(['source' => 'site', 'compact' => false])
<section id="{{ $compact ? 'newsletter-inline' : 'newsletter' }}" class="relative overflow-hidden border-y border-gold/40 bg-ink-2">
    <div class="wrap grid gap-8 py-12 md:py-16 {{ $compact ? '' : 'lg:grid-cols-[1.1fr_1fr] lg:items-center lg:gap-16' }}">
        <div>
            <p class="kicker">Free · Every weekday morning</p>
            <h2 class="hed mt-3 text-[40px] md:text-[56px]">THE DAILY<br><span class="text-gold">EXTRACT</span></h2>
            <p class="mt-4 max-w-md text-[17px] leading-relaxed text-fog">The stories, policies and market movements shaping digital finance in Africa. Delivered straight to your inbox.</p>
        </div>
        <div>
            <form x-data="newsletter(@js($source))" @submit.prevent="submit" action="{{ route('newsletter.store') }}" method="post" novalidate>
                @csrf
                <input type="hidden" name="source" value="{{ $source }}">
                <label for="nl-{{ $source }}" class="meta">Email address</label>
                <div class="mt-2 flex flex-col gap-2 sm:flex-row">
                    <input id="nl-{{ $source }}" x-model="email" name="email" type="email" required autocomplete="email" placeholder="you@company.com" class="input flex-1">
                    <button type="submit" class="btn-gold shrink-0" :disabled="status === 'loading'"><span x-text="status === 'loading' ? 'Joining…' : 'Subscribe'">Subscribe</span></button>
                </div>
                <p x-cloak x-show="status === 'ok'" x-transition class="mt-3 flex items-center gap-2 text-[14px] text-up"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 5 5 9-10"/></svg><span x-text="message"></span></p>
                <p x-cloak x-show="status === 'error'" x-transition class="mt-3 text-[14px] text-down" x-text="message" role="alert"></p>
                @if (session('status') && ! $errors->any())<p class="mt-3 text-[14px] text-up">{{ session('status') }}</p>@endif
                <p class="mt-4 text-[12px] leading-relaxed text-mute">One email a day. No spam. Unsubscribe any time. See our <a href="{{ route('privacy') }}" class="underline hover:text-gold">privacy policy</a>.</p>
            </form>
            <x-ad placement="newsletter_sponsor" class="mt-6" />
        </div>
    </div>
</section>
