@extends('layouts.admin')
@section('title', 'Пользователи')
@section('actions')<a href="{{ route('admin.users.create') }}" class="btn btn-primary"><x-icon name="plus" size="16"/> Добавить</a>@endsection
@section('content')
<form class="filters card" method="GET">
  <div class="field"><label>Поиск</label><input name="q" value="{{ request('q') }}" placeholder="Имя, email, телефон"></div>
  <div class="field"><label>Роль</label><select name="role"><option value="">Все</option><option value="client" @selected(request('role') === 'client')>Клиент</option><option value="manager" @selected(request('role') === 'manager')>Менеджер</option><option value="admin" @selected(request('role') === 'admin')>Администратор</option></select></div>
  <button class="btn btn-primary">Найти</button>
</form>
<div class="table-wrap" style="margin-top:12px"><table class="t">
  <thead><tr><th>Имя</th><th>Email</th><th>Телефон</th><th>Роль</th><th>Заявок</th><th>Регистрация</th><th>Последний вход</th><th></th></tr></thead>
  <tbody>
  @foreach($items as $u)
    <tr>
      <td><b>{{ $u->name }}</b> @if($u->is_blocked)<span class="pill pill-red">заблокирован</span>@endif</td>
      <td>{{ $u->email }}</td><td>{{ $u->phone }}</td>
      <td><span class="pill {{ $u->role === 'admin' ? 'pill-violet' : ($u->role === 'manager' ? 'pill-teal' : 'pill-gray') }}">{{ ['admin' => 'Админ', 'manager' => 'Менеджер', 'client' => 'Клиент'][$u->role] ?? $u->role }}</span></td>
      <td>@if($u->service_requests_count)<a href="{{ route('admin.requests.index', ['q' => $u->phone ?: $u->email]) }}">{{ $u->service_requests_count }}</a>@else 0 @endif</td>
      <td class="muted">{{ $u->created_at->format('d.m.Y') }}</td>
      <td class="muted">{{ $u->last_login_at?->format('d.m.Y H:i') ?? '—' }}</td>
      <td style="text-align:right;white-space:nowrap">
        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm"><x-icon name="edit" size="14"/></a>
        @if($u->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline" onsubmit="return confirm('Удалить пользователя?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><x-icon name="trash" size="14"/></button></form>
        @endif
      </td>
    </tr>
  @endforeach
  </tbody>
</table></div>
{{ $items->links() }}
@endsection
