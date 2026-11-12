@extends('layouts.site')
@section('title', __('site.articles.title'))
@section('content')
<div class="page-hero"><div class="container"><div class="crumbs"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a> / {{ __('site.articles.title') }}</div><h1>{{ __('site.articles.title') }}</h1><p>{{ __('site.articles.label') }}</p></div></div>
<section class="page"><div class="container">
  @if($articles->count())
    <div class="art-grid">@foreach($articles as $a) @include('site._article-card', ['a' => $a]) @endforeach</div>
    {{ $articles->links() }}
  @else
    <div class="empty">{{ __('site.articles.empty') }}</div>
  @endif
</div></section>
@endsection
