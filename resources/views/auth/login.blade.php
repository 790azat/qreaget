@extends('layouts.site')
@section('title', __('site.auth.login_title'))
@section('content')
<div class="auth-wrap"><div class="auth">
  @include('auth._side')
  <form method="POST" action="{{ route('login') }}" class="main">
    @csrf
    <h2 style="font-size:1.6rem">{{ __('site.auth.login_title') }}</h2>
    @if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
    <div class="field"><label>{{ __('site.auth.email') }}</label><input type="email" name="email" value="{{ old('email') }}" required autofocus></div>
    <div class="field"><label>{{ __('site.auth.password') }}</label><input type="password" name="password" required></div>
    <label class="check"><input type="checkbox" name="remember" value="1"> {{ __('site.auth.remember') }}</label>
    <button class="btn btn-primary btn-block">{{ __('site.auth.submit_login') }}</button>
    @if(setting('registration_enabled') === '1')
      <p class="muted" style="text-align:center;margin:0">{{ __('site.auth.no_account') }} <a href="{{ route('register') }}">{{ __('site.nav.register') }}</a></p>
    @endif
  </form>
</div></div>
@endsection
