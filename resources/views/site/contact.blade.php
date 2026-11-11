@extends('layouts.site')
@section('title', __('site.nav.contact'))
@section('content')
<div class="page-hero"><div class="container"><div class="crumbs"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a> / {{ __('site.nav.contact') }}</div><h1>{{ __('site.contact.title') }}</h1></div></div>
<section class="contact page"><div class="container">@include('site._contact-block')</div></section>
@endsection
