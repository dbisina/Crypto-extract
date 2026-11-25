@extends('pages.shell')
@section('page_eyebrow', 'Partner with us')
@section('page_title', 'Advertise')
@section('page_intro', 'Reach decision-makers, traders, founders and policy professionals following digital finance in Africa.')
@section('page_body')
<h2>Inventory</h2>
<ul>
    <li><strong>Header leaderboard</strong> (728×90): top of every inner page.</li>
    <li><strong>Homepage banner</strong> (970×250): the premium position beneath the main stories.</li>
    <li><strong>Homepage sidebar</strong> (300×250): beside the latest-news feed.</li>
    <li><strong>In-article</strong> (728×90): within the reading flow of every article.</li>
    <li><strong>Between-content</strong> (970×120): between major homepage sections.</li>
    <li><strong>Newsletter sponsorship</strong>: a single-sponsor slot in The Daily Extract.</li>
    <li><strong>Sponsored articles</strong>: clearly labelled branded content written with your team.</li>
</ul>
<h2>Our standards</h2>
<p>All paid placements are labelled. Sponsors never influence coverage. We reserve the right to decline advertisers that mislead consumers or promote unlicensed financial products. See our <a href="{{ route('transparency') }}">transparency page</a>.</p>
<h2>Request a media kit</h2>
<p>Tell us about your campaign through the <a href="{{ route('contact', ['topic' => 'Advertising']) }}">contact form</a> and choose “Advertising”. Our partnerships team will reply with rates and availability.</p>
<p><a href="{{ route('contact', ['topic' => 'Advertising']) }}" class="btn-gold !no-underline !text-ink">Contact partnerships</a></p>
@endsection
