@extends('pages.shell')
@section('page_eyebrow', 'Standards')
@section('page_title', 'Transparency')
@section('page_intro', 'How we make money, who owns us, how we use data and how we label content.')
@section('page_body')
<h2>Funding</h2>
<p>KryptoExtract earns revenue from display advertising, sponsored content and newsletter sponsorship. We do not accept payment, tokens or equity in exchange for editorial coverage.</p>
<h2>How content is labelled</h2>
<ul>
    <li><strong>Advertisement</strong>: banners and display units are marked “Advertisement”.</li>
    <li><strong>Sponsored</strong>: articles paid for by a partner, with the sponsor named.</li>
    <li><strong>Press release</strong>: announcements published as supplied.</li>
    <li><strong>Opinion / Insights</strong>: signed commentary that reflects the author's view.</li>
</ul>
<h2>Market data</h2>
<p>Prices shown in our ticker and market summary come from the public CoinGecko API and are cached for about two minutes. They are indicative, may be delayed and are not suitable for trading decisions. If the data service is unavailable we show an “unavailable” state rather than placeholder numbers.</p>
<h2>Demonstration content</h2>
<p>This installation currently contains demonstration articles, authors and advertisements used to showcase the design. They are not real reporting, and all companies named in them are fictional.</p>
<h2>Data we collect</h2>
<p>See our <a href="{{ route('privacy') }}">privacy policy</a> for the information we collect and how we use it.</p>
@endsection
