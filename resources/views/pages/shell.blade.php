@extends('layouts.app')
@section('title', $__env->yieldContent('page_title'))
@section('description', $__env->yieldContent('page_intro'))

@section('content')
@include('partials.page-head', ['eyebrow' => trim($__env->yieldContent('page_eyebrow')) ?: 'KryptoExtract', 'title' => trim($__env->yieldContent('page_title')), 'intro' => trim($__env->yieldContent('page_intro')), 'crumbs' => [[trim($__env->yieldContent('page_title'))]]])
<section class="wrap py-12 md:py-16">
    <div class="mx-auto max-w-[740px]">
        <div class="prose-kx">@yield('page_body')</div>
    </div>
    @yield('after')
</section>
@endsection
