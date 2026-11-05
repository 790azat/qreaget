@extends('layouts.admin')
@section('title', 'Обзор')
@section('actions')<a href="{{ route('admin.requests.create') }}" class="btn btn-primary"><x-icon name="plus" size="16"/> Новая заявка</a>@endsection
@section('content')
@php extract(\App\Support\Labels::all()); @endphp
<div class="grid g6" style="margin-bottom:16px">
  <div class="card stat hl"><small>Новые заявки</small><b>{{ $stats['requests_new'] }}</b></div>
  <div class="card stat"><small>Всего заявок</small><b>{{ $stats['requests_total'] }}</b></div>
  <div class="card stat"><small>За этот месяц</small><b>{{ $stats['requests_month'] }}</b></div>
  <div class="card stat"><small>Клиентов</small><b>{{ $stats['clients'] }}</b></div>
  <div class="card stat"><small>Новые сообщения</small><b>{{ $stats['chats_unread'] }}</b></div>
  <div class="card stat"><small>Открытые чаты</small><b>{{ $stats['chats_open'] }}</b></div>
</div>
<div class="grid g-main">
  <div class="grid">
    <div class="card">
      <h2>Заявки за 14 дней</h2>
      @php $max = max(1, $chart->max('count')); @endphp
      <div class="bars">@foreach($chart as $c)<div style="height:{{ max(2, $c['count'] / $max * 100) }}%" title="{{ $c['day'] }}"><span>{{ $c['count'] ?: '' }}</span></div>@endforeach</div>
      <div class="bars-x">@foreach($chart as $c)<span>{{ \Carbon\Carbon::parse($c['day'])->format('d') }}</span>@endforeach</div>
    </div>
    <div class="card" style="padding:0">
      <div style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px 6px"><h2 style="margin:0">Последние заявки</h2><a href="{{ route('admin.requests.index') }}">Все →</a></div>
      <div style="overflow-x:auto"><table class="t">
        <thead><tr><th>№</th><th>Клиент</th><th>Отдел</th><th>Статус</th><th>Дата</th></tr></thead>
        <tbody>
        @forelse($latest as $r)
          <tr><td><a href="{{ route('admin.requests.show', $r) }}"><b>{{ $r->number }}</b></a></td><td>{{ $r->name }}<br><small class="muted">{{ $r->phone }}</small></td><td>{{ $r->department?->name_ru ?? '—' }}</td><td><span class="pill pill-{{ $r->statusColor() }}">{{ $statusLabels[$r->status] ?? $r->status }}</span></td><td class="muted">{{ $r->created_at->format('d.m H:i') }}</td></tr>
        @empty
          <tr><td colspan="5" class="muted" style="text-align:center;padding:30px">Заявок пока нет</td></tr>
        @endforelse
        </tbody>
      </table></div>
    </div>
  </div>
  <div class="grid">
    <div class="card">
      <h2>По статусам</h2>
      @php $tot = max(1, array_sum($byStatus->all())); @endphp
      @foreach($statusLabels as $k => $v)
        <div class="hbar"><span>{{ $v }}</span><b style="text-align:right">{{ $byStatus[$k] ?? 0 }}</b><div class="track"><i style="width:{{ ($byStatus[$k] ?? 0) / $tot * 100 }}%"></i></div></div>
      @endforeach
    </div>
    <div class="card">
      <h2>По отделам</h2>
      @php $dm = max(1, $byDept->max('service_requests_count')); @endphp
      @foreach($byDept as $d)
        <div class="hbar"><span>{{ $d->name_ru }}</span><b style="text-align:right">{{ $d->service_requests_count }}</b><div class="track"><i style="width:{{ $d->service_requests_count / $dm * 100 }}%"></i></div></div>
      @endforeach
    </div>
    <div class="card">
      <div style="display:flex;justify-content:space-between"><h2>Чаты</h2><a href="{{ route('admin.chats.index') }}">Все →</a></div>
      @forelse($chats as $c)
        <a href="{{ route('admin.chats.show', $c) }}" style="display:block;padding:8px 0;border-bottom:1px solid var(--line);color:inherit">
          <b>{{ $c->displayName() }}</b> @if($c->unread_admin)<span class="pill pill-red">новое</span>@endif
          <div class="muted" style="font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $c->lastMessage?->body }}</div>
        </a>
      @empty
        <p class="muted">Нет чатов</p>
      @endforelse
    </div>
  </div>
</div>
@endsection
