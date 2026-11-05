@extends('layouts.site')
@section('title', $r->number)
@section('content')
@include('account._nav')
<div class="container two-col" style="padding-bottom:96px">
  <div class="card" style="box-shadow:none">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px">
      <h2 style="margin:0;font-size:1.5rem">{{ $r->number }}</h2>
      <span class="pill pill-{{ $r->statusColor() }}">{{ __('site.status.'.$r->status) }}</span>
    </div>
    <dl class="dl">
      <dt>{{ __('site.account.date') }}</dt><dd>{{ $r->created_at->format('d.m.Y H:i') }}</dd>
      <dt>{{ __('site.account.department') }}</dt><dd>{{ $r->department?->t('name') ?? '—' }}</dd>
      @if($r->price)<dt>{{ __('site.account.price') }}</dt><dd>{{ number_format($r->price, 0, '.', ' ') }} ֏</dd>@endif
      @if($r->deadline)<dt>{{ __('site.account.deadline') }}</dt><dd>{{ $r->deadline->format('d.m.Y') }}</dd>@endif
      <dt>{{ __('site.account.message') }}</dt><dd style="white-space:pre-wrap;font-weight:400">{{ $r->message }}</dd>
    </dl>
    <hr style="border:0;border-top:1px solid var(--line);margin:24px 0">
    <form method="POST" action="{{ route('account.requests.comment', $r) }}">
      @csrf
      <div class="field"><label>{{ __('site.account.add_comment') }}</label><textarea name="body" required maxlength="3000" style="min-height:90px"></textarea></div>
      <button class="btn btn-primary btn-sm" style="margin-top:10px">{{ __('site.account.send') }}</button>
    </form>
  </div>
  <aside class="card" style="box-shadow:none">
    <h3>{{ __('site.account.history') }}</h3>
    <ul class="timeline">
      @foreach($events as $e)
        <li>
          <b>{{ __('site.account.events.'.$e->type) }}</b>
          @if($e->type === 'status') — <span class="pill pill-gray">{{ __('site.status.'.$e->body) }}</span>
          @elseif($e->body)<div style="white-space:pre-wrap;font-size:.92rem">{{ $e->body }}</div>@endif
          <br><small>{{ $e->created_at->format('d.m.Y H:i') }}</small>
        </li>
      @endforeach
    </ul>
  </aside>
</div>
@endsection
