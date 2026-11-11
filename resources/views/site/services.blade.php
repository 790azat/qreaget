@extends('layouts.site')
@section('title', __('site.nav.services'))
@section('content')
<div class="page-hero"><div class="container"><div class="crumbs"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a> / {{ __('site.nav.services') }}</div><h1>{{ __('site.services.title') }}</h1><p>{{ __('site.services.text') }}</p></div></div>
<section class="page"><div class="container">
  <div class="svc-grid">
    @foreach($departments as $i => $d)
      <a href="{{ route('service', $d->slug) }}" class="svc">
        <span class="num n">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
        <span class="ic"><x-icon :name="$d->icon" size="26"/></span>
        <h3>{{ $d->t('name') }}</h3><p>{{ $d->t('description') }}</p>
        <span class="more">{{ __('site.services.more') }} <x-icon name="arrow" size="16"/></span>
      </a>
    @endforeach
  </div>
  <div class="cta-band" style="margin-top:56px"><h2>{{ __('site.contact.title') }}</h2><a href="{{ route('home') }}#contact" class="btn">{{ __('site.nav.request') }}</a></div>
</div></section>
@endsection
