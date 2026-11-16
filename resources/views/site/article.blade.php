@extends('layouts.site')
@section('title', $article->t('title'))
@section('description', $article->t('excerpt'))
@section('content')
<div class="page-hero"><div class="container">
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a> / <a href="{{ route('articles') }}">{{ __('site.articles.title') }}</a></div>
  <h1>{{ $article->t('title') }}</h1><p>{{ optional($article->published_at)->format('d.m.Y') }}</p>
</div></div>
<section class="page"><div class="container two-col">
  <article class="card prose" style="box-shadow:none">
    @if($article->image_url)<img src="{{ $article->image_url }}" alt="" style="border-radius:12px;margin-bottom:24px">@endif
    @if($article->t('excerpt'))<p style="font-size:1.15rem;color:var(--g700)"><b>{{ $article->t('excerpt') }}</b></p>@endif
    {!! nl2br(e($article->t('body'))) !!}
  </article>
  <aside>
    @if($more->count())
      <h4 style="font-family:var(--sans);font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)">{{ __('site.articles.more') }}</h4>
      <div style="display:grid;gap:16px">@foreach($more as $a) @include('site._article-card', ['a' => $a]) @endforeach</div>
    @endif
    @include('site._cta-band', ['compact' => true, 'style' => 'margin-top:24px'])
  </aside>
</div></section>
@endsection
