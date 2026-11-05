@extends('layouts.site')
@section('title', __('site.account.title'))
@section('content')
@include('account._nav')
<div class="container" style="padding-bottom:96px">
  @if($requests->count())
    <div class="table-wrap"><table class="t">
      <thead><tr><th>{{ __('site.account.number') }}</th><th>{{ __('site.account.date') }}</th><th>{{ __('site.account.department') }}</th><th>{{ __('site.account.status') }}</th><th></th></tr></thead>
      <tbody>
        @foreach($requests as $r)
          <tr>
            <td><b>{{ $r->number }}</b></td>
            <td>{{ $r->created_at->format('d.m.Y') }}</td>
            <td>{{ $r->department?->t('name') ?? '—' }}</td>
            <td><span class="pill pill-{{ $r->statusColor() }}">{{ __('site.status.'.$r->status) }}</span></td>
            <td style="text-align:right"><a href="{{ route('account.requests.show', $r) }}" class="btn btn-outline btn-sm">{{ __('site.account.view') }}</a></td>
          </tr>
        @endforeach
      </tbody>
    </table></div>
    {{ $requests->links() }}
  @else
    <div class="card empty" style="box-shadow:none">
      <x-icon name="file" size="48"/>
      <p style="margin-top:12px">{{ __('site.account.empty') }}</p>
      <a href="{{ route('account.requests.create') }}" class="btn btn-primary">{{ __('site.account.new_request') }}</a>
    </div>
  @endif
</div>
@endsection
