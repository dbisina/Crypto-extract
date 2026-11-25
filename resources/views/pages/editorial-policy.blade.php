@extends('pages.shell')
@section('page_eyebrow', 'Standards')
@section('page_title', 'Editorial policy')
@section('page_intro', 'The principles that govern how KryptoExtract reports, edits and publishes.')
@section('page_body')
<h2>Independence</h2>
<p>Our newsroom decides what to cover. Advertisers, sponsors, exchanges and token issuers cannot buy coverage, change a story or remove one. Commercial and editorial teams are separate.</p>
<h2>Accuracy and sourcing</h2>
<ul>
    <li>Factual claims are attributed to named sources wherever possible. Anonymous sources are used only when there is a clear public-interest reason, and are known to an editor.</li>
    <li>Numbers are checked against primary data: filings, regulator publications, on-chain records or exchange data.</li>
    <li>Where we cannot verify a claim, we say so.</li>
</ul>
<h2>Conflicts of interest</h2>
<p>Journalists must disclose any holdings of the assets they cover to their editor and must not trade ahead of publication. Relevant interests are disclosed in the story when material.</p>
<h2>Sponsored content and press releases</h2>
<p>Sponsored articles carry a visible <strong>Sponsored</strong> label and a notice naming the sponsor. Press releases carry a <strong>Press release</strong> label and are published as supplied. Neither is independent reporting, and neither is written by the newsroom.</p>
<h2>Not investment advice</h2>
<p>KryptoExtract provides news and analysis for information. Nothing we publish is a recommendation to buy or sell any asset.</p>
<h2>Corrections</h2>
<p>If we get something wrong we correct it promptly and visibly. See our <a href="{{ route('corrections') }}">corrections policy</a>.</p>
@endsection
