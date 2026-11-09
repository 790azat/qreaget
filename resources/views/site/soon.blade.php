@extends('layouts.site')
@section('title', 'Շուտով')
@section('content')
<div class="page-hero"><div class="container"><div class="crumbs"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a></div><h1>Բաժինը մշակման փուլում է</h1><p>Այս էջը շուտով հասանելի կլինի։</p></div></div>
<section class="page"><div class="container">
  <div class="empty">
    <p><a href="{{ route('home') }}" class="btn btn-outline btn-sm">{{ __('site.nav.home') }}</a></p>
  </div>
</div></section>
@endsection
