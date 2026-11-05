@extends('layouts.admin')
@section('title', 'Вопросы (FAQ)')
@section('actions')<a href="{{ route('admin.faqs.create') }}" class="btn btn-primary"><x-icon name="plus" size="16"/> Добавить</a>@endsection
@section('content')
<div class="table-wrap"><table class="t">
  <thead><tr><th>Порядок</th><th>Вопрос</th><th>Статус</th><th></th></tr></thead>
  <tbody>
  @foreach($items as $f)
    <tr>
      <td>{{ $f->sort }}</td>
      <td><b>{{ $f->question_hy }}</b><br><small class="muted">{{ $f->question_ru }}</small></td>
      <td>{!! $f->is_active ? '<span class="pill pill-green">Показан</span>' : '<span class="pill pill-gray">Скрыт</span>' !!}</td>
      <td style="text-align:right;white-space:nowrap">
        <a href="{{ route('admin.faqs.edit', $f) }}" class="btn btn-sm"><x-icon name="edit" size="14"/></a>
        <form method="POST" action="{{ route('admin.faqs.destroy', $f) }}" style="display:inline" onsubmit="return confirm('Удалить?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><x-icon name="trash" size="14"/></button></form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table></div>
@endsection
