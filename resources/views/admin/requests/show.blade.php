@extends('layouts.admin')
@section('title', 'Заявка '.$r->number)
@section('actions')
  <a href="{{ route('admin.requests.index') }}" class="btn">← К списку</a>
  <a href="{{ tel_href($r->phone) }}" class="btn"><x-icon name="phone" size="16"/> Позвонить</a>
  @if($r->email)<a href="mailto:{{ $r->email }}?subject={{ rawurlencode('Заявка '.$r->number) }}" class="btn"><x-icon name="mail" size="16"/> Написать</a>@endif
  @if(auth()->user()->isAdmin())
  <form method="POST" action="{{ route('admin.requests.destroy', $r) }}" onsubmit="return confirm('Удалить заявку?')">@csrf @method('DELETE')<button class="btn btn-danger"><x-icon name="trash" size="16"/></button></form>
  @endif
@endsection
@section('content')
@php extract(\App\Support\Labels::all()); @endphp
<div class="grid g-main">
  <div class="grid">
    <div class="card">
      <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap"><h2>Обращение клиента</h2><span class="muted">{{ $r->created_at->format('d.m.Y H:i') }} · язык: {{ strtoupper($r->locale) }} · IP {{ $r->ip }}</span></div>
      <p style="white-space:pre-wrap;margin:0">{{ $r->message }}</p>
      <p class="muted" style="margin:14px 0 0">Тип клиента: {{ $typeLabels[$r->client_type] ?? '—' }} @if($r->user) · Аккаунт: <a href="{{ route('admin.users.edit', $r->user) }}">{{ $r->user->email }}</a>@endif</p>
    </div>
    <form method="POST" action="{{ route('admin.requests.update', $r) }}" class="card">
      @csrf @method('PUT')
      <h2>Обработка</h2>
      <div class="grid g3">
        <div class="field"><label>Статус</label><select name="status">@foreach($statusLabels as $k => $v)<option value="{{ $k }}" @selected($r->status === $k)>{{ $v }}</option>@endforeach</select></div>
        <div class="field"><label>Приоритет</label><select name="priority">@foreach($priorityLabels as $k => $v)<option value="{{ $k }}" @selected($r->priority === $k)>{{ $v }}</option>@endforeach</select></div>
        <div class="field"><label>Ответственный</label><select name="assigned_to"><option value="">—</option>@foreach($staff as $s)<option value="{{ $s->id }}" @selected($r->assigned_to === $s->id)>{{ $s->name }}</option>@endforeach</select></div>
        <div class="field"><label>Отдел</label><select name="department_id"><option value="">—</option>@foreach($departments as $d)<option value="{{ $d->id }}" @selected($r->department_id === $d->id)>{{ $d->name_ru }}</option>@endforeach</select></div>
        <div class="field"><label>Стоимость (֏)</label><input type="number" step="1" min="0" name="price" value="{{ $r->price ? (int) $r->price : '' }}"></div>
        <div class="field"><label>Срок</label><input type="date" name="deadline" value="{{ $r->deadline?->format('Y-m-d') }}"></div>
        <div class="field"><label>Имя</label><input name="name" value="{{ $r->name }}" required></div>
        <div class="field"><label>Телефон</label><input name="phone" value="{{ $r->phone }}" required></div>
        <div class="field"><label>Email</label><input type="email" name="email" value="{{ $r->email }}"></div>
      </div>
      <div class="field" style="margin-top:12px"><label>Внутренняя заметка (клиент не видит)</label><textarea name="admin_note" rows="3">{{ $r->admin_note }}</textarea></div>
      <button class="btn btn-primary" style="margin-top:12px">Сохранить</button>
      <span class="muted" style="font-size:.8rem;margin-left:8px">Стоимость, срок и смена статуса видны клиенту в личном кабинете.</span>
    </form>
    <form method="POST" action="{{ route('admin.requests.event', $r) }}" class="card">
      @csrf
      <h2>Комментарий</h2>
      <textarea name="body" required rows="3" placeholder="Текст комментария"></textarea>
      <div style="display:flex;gap:12px;align-items:center;margin-top:10px">
        <label class="check"><input type="checkbox" name="visible_to_client" value="1" {{ $r->user_id ? 'checked' : '' }}> Показать клиенту в кабинете</label>
        <button class="btn btn-primary" style="margin-left:auto">Добавить</button>
      </div>
    </form>
  </div>
  <div class="grid">
    <div class="card">
      <h2>История</h2>
      <ul class="timeline">
        @foreach($r->events as $e)
          <li>
            <b>{{ ['created' => 'Создана', 'status' => 'Статус', 'comment' => 'Комментарий', 'client_comment' => 'Комментарий клиента'][$e->type] ?? $e->type }}</b>
            @if($e->type === 'status') → {{ $statusLabels[$e->body] ?? $e->body }}@elseif($e->body)<div style="white-space:pre-wrap">{{ $e->body }}</div>@endif
            <br><small>{{ $e->created_at->format('d.m.Y H:i') }} · {{ $e->user?->name ?? 'гость' }} @unless($e->visible_to_client) · 🔒 внутреннее @endunless</small>
          </li>
        @endforeach
      </ul>
    </div>
    @if($others->count())
    <div class="card">
      <h2>Другие заявки клиента</h2>
      @foreach($others as $o)
        <div style="padding:6px 0;border-bottom:1px solid var(--line)"><a href="{{ route('admin.requests.show', $o) }}">{{ $o->number }}</a> <span class="pill pill-{{ $o->statusColor() }}">{{ $statusLabels[$o->status] ?? '' }}</span> <small class="muted">{{ $o->created_at->format('d.m.Y') }}</small></div>
      @endforeach
    </div>
    @endif
  </div>
</div>
@endsection
