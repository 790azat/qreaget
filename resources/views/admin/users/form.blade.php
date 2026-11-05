@extends('layouts.admin')
@section('title', $item->exists ? 'Пользователь: '.$item->name : 'Новый пользователь')
@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.users.update', $item) : route('admin.users.store') }}" class="card grid g2" style="max-width:800px">
  @csrf @if($item->exists) @method('PUT') @endif
  <div class="field"><label>Имя *</label><input name="name" required value="{{ old('name', $item->name) }}"></div>
  <div class="field"><label>Email *</label><input type="email" name="email" required value="{{ old('email', $item->email) }}"></div>
  <div class="field"><label>Телефон</label><input name="phone" value="{{ old('phone', $item->phone) }}"></div>
  <div class="field"><label>Роль</label><select name="role" @disabled($item->id === auth()->id())>
    <option value="client" @selected($item->role === 'client')>Клиент</option>
    <option value="manager" @selected($item->role === 'manager')>Менеджер (заявки и чат)</option>
    <option value="admin" @selected($item->role === 'admin')>Администратор (полный доступ)</option>
  </select>@if($item->id === auth()->id())<input type="hidden" name="role" value="{{ $item->role }}">@endif</div>
  <div class="field"><label>{{ $item->exists ? 'Новый пароль' : 'Пароль *' }}</label><input type="password" name="password" minlength="8" placeholder="{{ $item->exists ? 'не менять' : '' }}" @required(!$item->exists)></div>
  <div class="field" style="justify-content:end"><label class="check"><input type="checkbox" name="is_blocked" value="1" @checked($item->is_blocked) @disabled($item->id === auth()->id())> Заблокировать доступ</label></div>
  <div style="grid-column:1/-1;display:flex;gap:8px"><button class="btn btn-primary">Сохранить</button><a href="{{ route('admin.users.index') }}" class="btn">Отмена</a></div>
</form>
@endsection
