@extends('pages.shell')
@section('page_eyebrow', 'Standards')
@section('page_title', 'Corrections policy')
@section('page_intro', 'We make mistakes. When we do, we fix them quickly and say so.')
@section('page_body')
<h2>How we correct</h2>
<ul>
    <li><strong>Factual errors</strong> are corrected in the article and flagged with an “Updated” date on the story.</li>
    <li><strong>Significant errors</strong> that change the meaning of a story are noted explicitly at the top or bottom of the article.</li>
    <li><strong>Headlines and summaries</strong> that prove misleading are amended.</li>
    <li>We do not silently remove or rewrite published reporting.</li>
</ul>
<h2>Report an error</h2>
<p>Send details, with a link to the story and the correct information, through our <a href="{{ route('contact') }}">contact form</a> (choose “Correction”). An editor will review your request.</p>
<h2>Takedown requests</h2>
<p>We do not remove accurate reporting because it is unwelcome. Requests relating to privacy, safety or legal concerns are reviewed individually by senior editors.</p>
@endsection
