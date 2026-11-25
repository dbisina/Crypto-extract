@extends('admin.layout')
@php $isNew = ! $article->exists; $v = fn ($k, $d = null) => old($k, $article->{$k} ?? $d); @endphp
@section('title', $isNew ? 'New article' : 'Edit article')
@section('eyebrow', 'Articles')
@section('heading', $isNew ? 'New article' : 'Edit article')
@push('head') @vite('resources/js/admin.js') @endpush
@section('actions')
    <a href="{{ route('admin.articles.index') }}" class="btn-line !px-4 !py-2.5">← All articles</a>
    @unless ($isNew)<a href="{{ $article->url }}" target="_blank" class="btn-line !px-4 !py-2.5">View ↗</a>@endunless
@endsection

@section('content')
<form method="post" enctype="multipart/form-data" action="{{ $isNew ? route('admin.articles.store') : route('admin.articles.update', $article) }}"
      x-data="{ status: @js($v('status', 'draft')), imagePath: @js($v('image_path', $article->image && ! str_starts_with((string) $article->image, 'http') ? $article->image : '')), preview: @js($article->image_url), picker: false, media: @js($media), sponsored: {{ $v('is_sponsored') ? 'true' : 'false' }} }">
    @csrf @unless ($isNew) @method('PUT') @endunless
    <div class="grid gap-8 xl:grid-cols-[1fr_340px]">
        {{-- Main column --}}
        <div class="min-w-0 space-y-6">
            <div><label class="meta" for="title">Headline</label>
                <input id="title" name="title" value="{{ $v('title') }}" required class="input mt-2 !py-4 font-display text-[26px] font-bold" placeholder="A headline that earns the click and the trust"></div>
            <div><label class="meta" for="excerpt">Excerpt <span class="normal-case tracking-normal text-mute">(shown as the standfirst and on cards)</span></label>
                <textarea id="excerpt" name="excerpt" rows="3" class="input mt-2">{{ $v('excerpt') }}</textarea></div>
            <div>
                <label class="meta" for="body-input">Article body</label>
                <div class="mt-2"><input id="body-input" type="hidden" name="body" value="{{ old('body', $article->body) }}"><trix-editor input="body-input" class="trix-content" placeholder="Write the story…"></trix-editor></div>
                <p class="mt-2 text-[12px] text-mute">Tip: use the Heading button for section headings (published as H2), the quote button for pull quotes, and the link button for sources.</p>
            </div>
            <div><label class="meta" for="sources">Sources &amp; references <span class="normal-case tracking-normal text-mute">(one per line; URLs become links)</span></label>
                <textarea id="sources" name="sources" rows="4" class="input mt-2 font-mono text-[13px]">{{ $v('sources') }}</textarea></div>

            <fieldset class="border border-line bg-card p-6">
                <legend class="kicker px-2">SEO &amp; sharing</legend>
                <div class="space-y-5">
                    <div><label class="meta" for="slug">URL slug</label>
                        <div class="mt-2 flex items-center border border-line-2 bg-ink-2"><span class="px-3 font-mono text-[12px] text-mute">/news/</span><input id="slug" name="slug" value="{{ $v('slug') }}" class="w-full bg-transparent py-3 pr-3 text-[14px] focus:outline-none" placeholder="auto-generated from headline"></div></div>
                    <div x-data="{ n: {{ strlen($v('seo_title', '')) }} }"><label class="meta" for="seo_title">SEO title <span class="normal-case tracking-normal text-mute" x-text="n + '/70'"></span></label>
                        <input id="seo_title" name="seo_title" maxlength="70" @input="n = $event.target.value.length" value="{{ $v('seo_title') }}" class="input mt-2" placeholder="Defaults to the headline"></div>
                    <div x-data="{ n: {{ strlen($v('meta_description', '')) }} }"><label class="meta" for="meta_description">Meta description <span class="normal-case tracking-normal text-mute" x-text="n + '/170'"></span></label>
                        <textarea id="meta_description" name="meta_description" maxlength="170" rows="2" @input="n = $event.target.value.length" class="input mt-2" placeholder="Defaults to the excerpt">{{ $v('meta_description') }}</textarea></div>
                    <div class="flex flex-wrap items-center gap-4"><div class="grow"><label class="meta" for="og_file">Open Graph image (1200×630)</label><input id="og_file" name="og_file" type="file" accept="image/*" class="input mt-2 !p-2 text-[13px]"></div>
                        @if ($article->og_image)<img src="{{ \App\Models\Article::resolveImage($article->og_image) }}" alt="" class="h-16 w-28 object-cover">@endif</div>
                </div>
            </fieldset>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-6">
            <section class="border border-gold/50 bg-card p-5">
                <h2 class="kicker">Publish</h2>
                <div class="mt-4 space-y-4">
                    <div><label class="meta" for="status">Status</label>
                        <select id="status" name="status" x-model="status" class="input mt-2"><option value="draft">Draft</option><option value="published">Published</option><option value="scheduled">Scheduled</option></select></div>
                    <div x-show="status !== 'draft'" x-collapse><label class="meta" for="published_at"><span x-text="status === 'scheduled' ? 'Go live at' : 'Publication date'"></span></label>
                        <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}" class="input mt-2">
                        <p class="mt-1 text-[12px] text-mute">Leave blank to use now (published) or tomorrow (scheduled). Times are server time.</p></div>
                    <button class="btn-gold w-full"><span x-text="status === 'draft' ? 'Save draft' : (status === 'scheduled' ? 'Schedule article' : 'Publish article')">Save</span></button>
                </div>
            </section>

            <section class="border border-line bg-card p-5">
                <h2 class="kicker">Flags</h2>
                <div class="mt-4 space-y-3 text-[14px]">
                    @foreach ([['is_featured', 'Featured story (homepage hero)'], ['is_breaking', 'Breaking news'], ['is_press_release', 'Press release'], ['is_sponsored', 'Sponsored content']] as [$f, $l])
                        <label class="flex items-start gap-2.5"><input type="checkbox" name="{{ $f }}" value="1" @checked($v($f)) @if ($f === 'is_sponsored') x-model="sponsored" @endif class="mt-1 accent-[#C9A44C]"> {{ $l }}</label>
                    @endforeach
                    <div x-show="sponsored" x-collapse><label class="meta" for="sponsor_name">Sponsor name</label><input id="sponsor_name" name="sponsor_name" value="{{ $v('sponsor_name') }}" class="input mt-2"></div>
                </div>
            </section>

            <section class="border border-line bg-card p-5">
                <h2 class="kicker">Classification</h2>
                <div class="mt-4 space-y-4">
                    <div><label class="meta" for="category_id">Category</label><select id="category_id" name="category_id" class="input mt-2"><option value="">None</option>@foreach ($categories as $c)<option value="{{ $c->id }}" @selected($v('category_id') == $c->id)>{{ $c->name }}</option>@endforeach</select></div>
                    <div><label class="meta" for="author_id">Author</label><select id="author_id" name="author_id" class="input mt-2"><option value="">None</option>@foreach ($authors as $a)<option value="{{ $a->id }}" @selected($v('author_id') == $a->id)>{{ $a->name }}</option>@endforeach</select></div>
                    <div><label class="meta" for="country">Country</label><select id="country" name="country" class="input mt-2"><option value="">None / global</option>@foreach (\App\Models\Article::COUNTRIES as $c)<option @selected($v('country') === $c)>{{ $c }}</option>@endforeach</select></div>
                    <div><label class="meta" for="kind">Format</label><select id="kind" name="kind" class="input mt-2">@foreach (['news' => 'News', 'insight' => 'Insight / opinion', 'guide' => 'Learn guide'] as $k => $l)<option value="{{ $k }}" @selected($v('kind') === $k)>{{ $l }}</option>@endforeach</select></div>
                    <div><label class="meta" for="tags">Tags <span class="normal-case tracking-normal text-mute">(comma-separated)</span></label><input id="tags" name="tags" value="{{ old('tags', $tagString) }}" class="input mt-2" placeholder="Nigeria, Stablecoins, ETF"></div>
                </div>
            </section>

            <section class="border border-line bg-card p-5">
                <h2 class="kicker">Featured image</h2>
                <div class="mt-4 space-y-3">
                    <template x-if="preview"><img :src="preview" alt="" class="aspect-[16/9] w-full object-cover"></template>
                    <input type="hidden" name="image_path" :value="imagePath">
                    <input name="image_file" type="file" accept="image/*" class="input !p-2 text-[13px]" @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview; imagePath = ''">
                    <button type="button" @click="picker = true" class="btn-line w-full !py-2.5">Choose from media library</button>
                    <div><label class="meta" for="image_alt">Alt text</label><input id="image_alt" name="image_alt" value="{{ $v('image_alt') }}" class="input mt-2" placeholder="Describe the image for screen readers"></div>
                    <div><label class="meta" for="image_caption">Caption</label><input id="image_caption" name="image_caption" value="{{ $v('image_caption') }}" class="input mt-2"></div>
                </div>
            </section>

            @unless ($isNew)
                <button type="submit" form="delete-article" onclick="return confirm('Delete this article permanently?')" class="w-full border border-down/40 py-3 font-mono text-[11px] uppercase tracking-wider text-down transition-colors hover:bg-down hover:text-white">Delete article</button>
            @endunless
        </aside>
    </div>

    {{-- Media picker --}}
    <div x-cloak x-show="picker" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-5" @keydown.escape.window="picker = false">
        <div class="max-h-[85vh] w-full max-w-4xl overflow-auto border border-line bg-ink-2 p-6" @click.outside="picker = false">
            <div class="flex items-center justify-between"><h3 class="kicker">Media library</h3><button type="button" @click="picker = false" class="meta hover:text-gold">Close ✕</button></div>
            <p x-show="!media.length" class="py-12 text-center text-fog">No uploads yet. Upload an image above or in the Media section.</p>
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                <template x-for="m in media" :key="m.path">
                    <button type="button" @click="imagePath = m.path; preview = m.url; picker = false" class="group aspect-square overflow-hidden border border-line-2 hover:border-gold"><img :src="m.url" alt="" class="size-full object-cover"></button>
                </template>
            </div>
        </div>
    </div>
</form>
@unless ($isNew)<form id="delete-article" method="post" action="{{ route('admin.articles.destroy', $article) }}">@csrf @method('DELETE')</form>@endunless
@endsection
