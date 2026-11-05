@extends('layouts.site')
@section('title', __('site.account.profile'))
@section('content')
@include('account._nav')
<div class="container" style="padding-bottom:96px;max-width:760px">
  <form method="POST" action="{{ route('account.profile.update') }}" class="card" style="box-shadow:none">
    @csrf @method('PUT')
    @if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
    <div class="form-grid">
      <div class="field full"><label>{{ __('site.auth.name') }}</label><input name="name" value="{{ old('name', $user->name) }}" required></div>
      <div class="field"><label>{{ __('site.auth.email') }}</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
      <div class="field"><label>{{ __('site.auth.phone') }}</label><input name="phone" value="{{ old('phone', $user->phone) }}" required></div>
      <div class="full"><h3 style="margin-top:12px">{{ __('site.account.change_password') }}</h3></div>
      <div class="field full"><label>{{ __('site.account.current_password') }}</label><input type="password" name="current_password"></div>
      <div class="field"><label>{{ __('site.account.new_password') }}</label><input type="password" name="password" minlength="8"></div>
      <div class="field"><label>{{ __('site.auth.password_confirm') }}</label><input type="password" name="password_confirmation" minlength="8"></div>
      <div class="full"><button class="btn btn-primary">{{ __('site.account.save') }}</button></div>
    </div>
  </form>
</div>
@endsection
