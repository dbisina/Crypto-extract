@extends('pages.shell')
@section('page_eyebrow', 'Legal')
@section('page_title', 'Privacy policy')
@section('page_intro', 'What we collect, why we collect it and the choices you have.')
@section('page_body')
<p class="lede">Template text for review. Have it checked by qualified counsel before relying on it, including against Nigeria's NDPA, Kenya's Data Protection Act, Ghana's Data Protection Act and South Africa's POPIA.</p>
<h2>Information we collect</h2>
<ul>
    <li><strong>Newsletter:</strong> your email address when you subscribe to The Daily Extract.</li>
    <li><strong>Contact and tip forms:</strong> the details you choose to send us. Tip submissions can be anonymous.</li>
    <li><strong>Basic technical data:</strong> standard server logs (IP address, browser, pages requested) used for security and aggregate analytics.</li>
</ul>
<h2>How we use it</h2>
<p>To send the newsletter you requested, respond to your messages, review tips, secure the site and understand which stories are read. We do not sell your personal data.</p>
<h2>Third parties</h2>
<p>Fonts are served by Google Fonts. Market prices are fetched server-side from CoinGecko. Advertisers receive no personal information from us; clicking an advertisement takes you to the advertiser's own site, which has its own policy.</p>
<h2>Your rights</h2>
<p>You may request access to, correction of or deletion of your personal data, and unsubscribe from the newsletter at any time, by <a href="{{ route('contact', ['topic' => 'Privacy']) }}">contacting us</a>.</p>
<h2>Retention and security</h2>
<p>We keep data only as long as needed for the purposes above and use reasonable technical safeguards. No system is perfectly secure.</p>
@endsection
