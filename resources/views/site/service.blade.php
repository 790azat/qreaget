@extends('layouts.site')
@section('title', $department->t('name'))
@section('description', \Illuminate\Support\Str::limit($department->t('description'), 160))
@section('content')
<div class="page-hero"><div class="container">
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a> / <a href="{{ route('services') }}">{{ __('site.nav.services') }}</a> / {{ $department->t('name') }}</div>
  <h1>{{ $department->t('name') }}</h1>
</div></div>
<section class="page"><div class="container two-col">
  <div>
    <div class="card" style="box-shadow:none;margin-bottom:28px">
      <div style="display:flex;gap:18px;align-items:center;margin-bottom:18px"><span class="svc" style="padding:0;border:0;background:none"><span class="ic"><x-icon :name="$department->icon" size="28"/></span></span><h2 style="margin:0;font-size:1.6rem">{{ $department->t('name') }}</h2></div>
      <div class="prose">{!! nl2br(e($department->t('description'))) !!}</div>
    </div>
    @include('partials.request-form', ['departments' => $departments, 'selectedDepartment' => $department->id])
  </div>
  <aside class="card" style="box-shadow:none;padding:20px">
    <h4 style="font-family:var(--sans);font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)">{{ __('site.services.other') }}</h4>
    <div class="side-list">
      @foreach($departments as $d)
        <a href="{{ route('service', $d->slug) }}" class="{{ $d->id === $department->id ? 'on' : '' }}"><x-icon :name="$d->icon" size="18"/> {{ $d->t('name') }}</a>
      @endforeach
    </div>
  </aside>
</div></section>
@endsection
