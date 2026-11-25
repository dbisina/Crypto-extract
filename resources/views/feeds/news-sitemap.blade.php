<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
@foreach ($articles as $a)
    <url>
        <loc>{{ $a->url }}</loc>
        <news:news>
            <news:publication><news:name>KryptoExtract</news:name><news:language>en</news:language></news:publication>
            <news:publication_date>{{ $a->published_at->toIso8601String() }}</news:publication_date>
            <news:title>{{ $a->title }}</news:title>
        </news:news>
    </url>
@endforeach
</urlset>
