@extends('layouts.admin')
@section('title', 'Журнал действий')
@section('content')
<div class="table-wrap"><table class="t">
  <thead><tr><th>Время</th><th>Пользователь</th><th>Действие</th><th>Детали</th><th>IP</th></tr></thead>
  <tbody>
  @foreach($logs as $l)
    <tr><td class="muted">{{ $l->created_at->format('d.m.Y H:i:s') }}</td><td>{{ $l->user?->name ?? '—' }}</td><td><code>{{ $l->action }}</code></td><td>{{ \Illuminate\Support\Str::limit($l->details, 80) }}</td><td class="muted">{{ $l->ip }}</td></tr>
  @endforeach
  </tbody>
</table></div>
{{ $logs->links() }}
@endsection
