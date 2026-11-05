@extends('layouts.site')
@section('title', __('site.auth.register_title'))
@section('content')
<div class="auth-wrap"><div class="auth">
  @include('auth._side')
  <form method="POST" action="{{ route('register') }}" class="main">
    @csrf
    <h2 style="font-size:1.6rem">{{ __('site.auth.register_title') }}</h2>
    @if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
    <div class="field"><label>{{ __('site.auth.name') }}</label><input name="name" value="{{ old('name') }}" required maxlength="120"></div>
    <div class="form-grid">
      <div class="field"><label>{{ __('site.auth.email') }}</label><input type="email" name="email" value="{{ old('email') }}" required></div>
      <div class="field"><label>{{ __('site.auth.phone') }}</label><input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+374 ..."></div>
      <div class="field"><label>{{ __('site.auth.password') }}</label><input type="password" name="password" required minlength="8"></div>
      <div class="field"><label>{{ __('site.auth.password_confirm') }}</label><input type="password" name="password_confirmation" required minlength="8"></div>
    </div>
    <label class="check"><input type="checkbox" name="agree" value="1" required> {{ __('site.auth.agree') }}</label>
    <button class="btn btn-primary btn-block">{{ __('site.auth.submit_register') }}</button>
    <p class="muted" style="text-align:center;margin:0">{{ __('site.auth.have_account') }} <a href="{{ route('login') }}">{{ __('site.nav.login') }}</a></p>
  </form>
</div></div>
@endsection
