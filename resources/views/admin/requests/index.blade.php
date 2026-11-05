@extends('layouts.admin')
@section('title', 'Заявки')
@section('actions')
  <a href="{{ route('admin.requests.export', request()->query()) }}" class="btn"><x-icon name="download" size="16"/> Экспорт CSV</a>
  <a href="{{ route('admin.requests.create') }}" class="btn btn-primary"><x-icon name="plus" size="16"/> Добавить</a>
@endsection
@section('content')
@php extract(\App\Support\Labels::all()); @endphp
<form class="filters card" method="GET">
  <div class="field"><label>Поиск</label><input name="q" value="{{ request('q') }}" placeholder="Имя, телефон, номер, текст"></div>
  <div class="field"><label>Статус</label><select name="status"><option value="">Все</option>@foreach($statusLabels as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach</select></div>
  <div class="field"><label>Отдел</label><select name="department_id"><option value="">Все</option>@foreach($departments as $d)<option value="{{ $d->id }}" @selected(request('department_id') == $d->id)>{{ $d->name_ru }}</option>@endforeach</select></div>
  <div class="field"><label>Приоритет</label><select name="priority"><option value="">Все</option>@foreach($priorityLabels as $k => $v)<option value="{{ $k }}" @selected(request('priority') === $k)>{{ $v }}</option>@endforeach</select></div>
  <div class="field"><label>Ответственный</label><select name="assigned_to"><option value="">Все</option>@foreach($staff as $s)<option value="{{ $s->id }}" @selected(request('assigned_to') == $s->id)>{{ $s->name }}</option>@endforeach</select></div>
  <div class="field"><label>С</label><input type="date" name="from" value="{{ request('from') }}"></div>
  <div class="field"><label>По</label><input type="date" name="to" value="{{ request('to') }}"></div>
  <button class="btn btn-primary">Найти</button>
  <a href="{{ route('admin.requests.index') }}" class="btn">Сброс</a>
</form>
<form method="POST" action="{{ route('admin.requests.bulk') }}" id="bulk">
  @csrf
  <div style="display:flex;gap:8px;margin:12px 0;align-items:center;flex-wrap:wrap">
    <span class="muted">Найдено: {{ $items->total() }}</span>
    <select name="action" style="width:auto;margin-left:auto">
      <option value="">С выбранными…</option>
      @foreach($statusLabels as $k => $v)<option value="{{ $k }}">→ {{ $v }}</option>@endforeach
      @if(auth()->user()->isAdmin())<option value="delete">Удалить</option>@endif
    </select>
    <button class="btn btn-sm" onclick="return document.querySelector('#bulk [name=action]').value==='delete'?confirm('Удалить выбранные?'):true">Применить</button>
  </div>
  <div class="table-wrap"><table class="t">
    <thead><tr><th><input type="checkbox" onclick="document.querySelectorAll('.rowchk').forEach(c=>c.checked=this.checked)"></th><th>№</th><th>Дата</th><th>Клиент</th><th>Отдел</th><th>Статус</th><th>Приоритет</th><th>Ответственный</th><th>Срок</th></tr></thead>
    <tbody>
      @forelse($items as $r)
        <tr>
          <td><input type="checkbox" class="rowchk" name="ids[]" value="{{ $r->id }}"></td>
          <td><a href="{{ route('admin.requests.show', $r) }}"><b>{{ $r->number }}</b></a>@if($r->user_id)<br><span class="pill pill-gray">аккаунт</span>@endif</td>
          <td class="muted">{{ $r->created_at->format('d.m.Y H:i') }}</td>
          <td>{{ $r->name }}<br><small class="muted">{{ $r->phone }} {{ $r->email }}</small></td>
          <td>{{ $r->department?->name_ru ?? '—' }}</td>
          <td><span class="pill pill-{{ $r->statusColor() }}">{{ $statusLabels[$r->status] ?? $r->status }}</span></td>
          <td>@if($r->priority === 'high')<span class="pill pill-red">Высокий</span>@else<span class="muted">{{ $priorityLabels[$r->priority] ?? '' }}</span>@endif</td>
          <td>{{ $r->assignee?->name ?? '—' }}</td>
          <td>{{ $r->deadline?->format('d.m.Y') ?? '—' }}</td>
        </tr>
      @empty
        <tr><td colspan="9" class="muted" style="text-align:center;padding:40px">Ничего не найдено</td></tr>
      @endforelse
    </tbody>
  </table></div>
</form>
{{ $items->links() }}
@endsection
