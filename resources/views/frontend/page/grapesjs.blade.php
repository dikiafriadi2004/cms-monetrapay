@extends('frontend.layouts.app')

@section('title', $page->meta_title ?: $page->title)

@section('content')
@if($page->gjs_css)
<style>
{!! $page->gjs_css !!}
</style>
@endif

<div class="gjs-page-content">
    {!! $page->content !!}
</div>
@endsection
