@extends('pages.shell')
@section('page_eyebrow', 'About')
@section('page_title', 'About KryptoExtract')
@section('page_intro', 'An independent publication covering cryptocurrency, blockchain and digital finance, written from Africa for a global audience.')
@section('page_body')
<p>KryptoExtract exists because the most interesting story in digital assets is not being told from where it is happening. Across Nigeria, Ghana, Kenya, South Africa and a growing list of other markets, stablecoins and crypto rails are being used for ordinary things: paying suppliers, sending remittances, protecting savings from currency risk.</p>
<p>We report on that reality with the standards expected of a serious financial publication: sourcing that can be checked, corrections that are public, and a clear line between journalism and advertising.</p>
<h2>What we cover</h2>
<ul>
    <li><strong>Africa</strong>: country-by-country reporting on adoption, exchanges, licensing and policy.</li>
    <li><strong>Markets</strong>: bitcoin, ether and the institutional flows that move them.</li>
    <li><strong>Regulation</strong>: central banks, securities regulators and the law of digital assets.</li>
    <li><strong>Stablecoins &amp; DeFi</strong>: the payment rails and financial protocols being built on public blockchains.</li>
    <li><strong>Insights</strong>: analysis, opinion and long-form reporting.</li>
</ul>
<h2>How we are funded</h2>
<p>We are supported by direct advertising and clearly labelled sponsored content. Advertisers have no influence over our reporting. Read more on our <a href="{{ route('transparency') }}">transparency page</a> and <a href="{{ route('editorial') }}">editorial policy</a>.</p>
<h2>Get in touch</h2>
<p>Tips, corrections and story ideas are welcome. Use our <a href="{{ route('tip') }}">tip form</a> or <a href="{{ route('contact') }}">contact the newsroom</a>.</p>
<p class="note">Note: the articles currently displayed on this site are demonstration content created to showcase the publication's design. They are not real reporting.</p>
@endsection
