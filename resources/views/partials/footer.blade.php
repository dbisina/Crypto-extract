@unless (request()->routeIs('home', 'article.show'))
    @include('partials.newsletter', ['source' => 'footer-band'])
@endunless
<footer class="mt-0 border-t border-line bg-ink">
    <div class="wrap pt-10"><x-ad placement="footer" class="mx-auto max-w-[728px]" /></div>
    <div class="wrap grid gap-12 py-14 md:grid-cols-12">
        <div class="md:col-span-4">
            <a href="{{ route('home') }}" class="font-display text-[34px] font-extrabold leading-none">Krypto<span class="text-gold">Extract</span></a>
            <p class="mt-4 max-w-xs text-[14px] leading-relaxed text-fog">An independent publication covering cryptocurrency, blockchain, stablecoins and digital finance, with Africa at the centre of the story.</p>
            <div class="mt-6 flex gap-5">
                <a href="https://x.com/kryptoextract" rel="noopener" class="meta hover:text-gold" aria-label="KryptoExtract on X">X</a>
                <a href="https://t.me/kryptoextract" rel="noopener" class="meta hover:text-gold">Telegram</a>
                <a href="https://www.linkedin.com/company/kryptoextract" rel="noopener" class="meta hover:text-gold">LinkedIn</a>
                <a href="{{ route('rss') }}" class="meta hover:text-gold">RSS</a>
            </div>
        </div>
        <nav class="grid grid-cols-2 gap-8 md:col-span-8 md:grid-cols-3" aria-label="Footer">
            <div>
                <h3 class="kicker">Sections</h3>
                <ul class="mt-4 space-y-2.5 text-[14px] text-fog">
                    @foreach ([['Latest', 'latest'], ['Africa', 'africa'], ['Markets', 'markets'], ['Bitcoin', 'bitcoin'], ['Regulation', 'regulation'], ['Stablecoins', 'stablecoins'], ['DeFi', 'defi'], ['Insights', 'insights'], ['Learn', 'learn']] as [$l, $r])
                        <li><a href="{{ route($r) }}" class="transition-colors hover:text-gold">{{ $l }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3 class="kicker">Editorial</h3>
                <ul class="mt-4 space-y-2.5 text-[14px] text-fog">
                    <li><a href="{{ route('about') }}" class="transition-colors hover:text-gold">About</a></li>
                    <li><a href="{{ route('editorial') }}" class="transition-colors hover:text-gold">Editorial policy</a></li>
                    <li><a href="{{ route('corrections') }}" class="transition-colors hover:text-gold">Corrections policy</a></li>
                    <li><a href="{{ route('transparency') }}" class="transition-colors hover:text-gold">Transparency</a></li>
                    <li><a href="{{ route('tip') }}" class="transition-colors hover:text-gold">Submit a tip</a></li>
                    <li><a href="{{ route('rss') }}" class="transition-colors hover:text-gold">RSS feed</a></li>
                </ul>
            </div>
            <div>
                <h3 class="kicker">Company</h3>
                <ul class="mt-4 space-y-2.5 text-[14px] text-fog">
                    <li><a href="{{ route('contact') }}" class="transition-colors hover:text-gold">Contact</a></li>
                    <li><a href="{{ route('advertise') }}" class="transition-colors hover:text-gold">Advertise</a></li>
                    <li><a href="{{ route('privacy') }}" class="transition-colors hover:text-gold">Privacy policy</a></li>
                    <li><a href="{{ route('terms') }}" class="transition-colors hover:text-gold">Terms &amp; conditions</a></li>
                    <li><a href="{{ route('sitemap') }}" class="transition-colors hover:text-gold">Sitemap</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="border-t border-line">
        <div class="wrap flex flex-col gap-3 py-6 md:flex-row md:items-center md:justify-between">
            <p class="meta">© {{ date('Y') }} KryptoExtract. All rights reserved.</p>
            <p class="max-w-2xl text-[12px] leading-relaxed text-mute">Nothing published here is investment, legal or tax advice. Digital assets are volatile and you may lose money. Articles currently shown are demonstration content, not real reporting.</p>
        </div>
    </div>
</footer>
