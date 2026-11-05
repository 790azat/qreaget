@extends('layouts.admin')
@section('title', 'Отделы / услуги')
@section('actions')<a href="{{ route('admin.departments.create') }}" class="btn btn-primary"><x-icon name="plus" size="16"/> Добавить</a>@endsection
@section('content')
<div class="table-wrap"><table class="t">
  <thead><tr><th>Порядок</th><th></th><th>Название (HY / RU / EN)</th><th>Адрес</th><th>Заявок</th><th>Статус</th><th></th></tr></thead>
  <tbody>
  @foreach($items as $d)
    <tr>
      <td>{{ $d->sort }}</td>
      <td><x-icon :name="$d->icon" size="22"/></td>
      <td><b>{{ $d->name_hy }}</b><br><small class="muted">{{ $d->name_ru }} · {{ $d->name_en }}</small></td>
      <td><a href="{{ route('service', $d->slug) }}" target="_blank">/services/{{ $d->slug }}</a></td>
      <td>{{ $d->service_requests_count }}</td>
      <td>{!! $d->is_active ? '<span class="pill pill-green">Показан</span>' : '<span class="pill pill-gray">Скрыт</span>' !!}</td>
      <td style="text-align:right;white-space:nowrap">
        <a href="{{ route('admin.departments.edit', $d) }}" class="btn btn-sm"><x-icon name="edit" size="14"/></a>
        <form method="POST" action="{{ route('admin.departments.destroy', $d) }}" style="display:inline" onsubmit="return confirm('Удалить отдел?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><x-icon name="trash" size="14"/></button></form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table></div>
@endsection
