<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
    <title>KryptoExtract</title>
    <link>{{ url('/') }}</link>
    <description>Independent African cryptocurrency and financial news.</description>
    <language>en</language>
    <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
    <atom:link href="{{ route('rss') }}" rel="self" type="application/rss+xml"/>
@foreach ($articles as $a)
    <item>
        <title>{{ $a->title }}</title>
        <link>{{ $a->url }}</link>
        <guid isPermaLink="true">{{ $a->url }}</guid>
        <pubDate>{{ $a->published_at->toRssString() }}</pubDate>
        <dc:creator>{{ $a->author->name ?? 'KryptoExtract' }}</dc:creator>
        @if ($a->category)<category>{{ $a->category->name }}</category>@endif
        <description>{{ $a->excerpt }}</description>
    </item>
@endforeach
</channel>
</rss>
