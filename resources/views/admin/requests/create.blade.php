@extends('layouts.admin')
@section('title', 'Новая заявка')
@section('content')
@php extract(\App\Support\Labels::all()); @endphp
<form method="POST" action="{{ route('admin.requests.store') }}" class="card grid g2" style="max-width:900px">
  @csrf
  <div class="field"><label>Имя *</label><input name="name" required value="{{ old('name') }}"></div>
  <div class="field"><label>Телефон *</label><input name="phone" required value="{{ old('phone') }}"></div>
  <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}"></div>
  <div class="field"><label>Тип клиента</label><select name="client_type"><option value="">—</option>@foreach($typeLabels as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select></div>
  <div class="field" style="grid-column:1/-1"><label>Отдел</label><select name="department_id"><option value="">—</option>@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name_ru }}</option>@endforeach</select></div>
  <div class="field" style="grid-column:1/-1"><label>Описание *</label><textarea name="message" required rows="6">{{ old('message') }}</textarea></div>
  <div><button class="btn btn-primary">Создать</button></div>
</form>
@endsection
