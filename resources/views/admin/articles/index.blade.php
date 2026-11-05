@extends('layouts.admin')
@section('title', 'Статьи')
@section('actions')<a href="{{ route('admin.articles.create') }}" class="btn btn-primary"><x-icon name="plus" size="16"/> Добавить</a>@endsection
@section('content')
<div class="table-wrap"><table class="t">
  <thead><tr><th>Заголовок</th><th>Дата</th><th>Статус</th><th></th></tr></thead>
  <tbody>
  @forelse($items as $a)
    <tr>
      <td><b>{{ $a->title_hy }}</b><br><small class="muted">{{ $a->title_ru }}</small></td>
      <td class="muted">{{ $a->published_at?->format('d.m.Y') }}</td>
      <td>{!! $a->is_published ? '<span class="pill pill-green">Опубликована</span>' : '<span class="pill pill-gray">Черновик</span>' !!}</td>
      <td style="text-align:right;white-space:nowrap">
        <a href="{{ route('article', $a->slug) }}" target="_blank" class="btn btn-sm"><x-icon name="eye" size="14"/></a>
        <a href="{{ route('admin.articles.edit', $a) }}" class="btn btn-sm"><x-icon name="edit" size="14"/></a>
        <form method="POST" action="{{ route('admin.articles.destroy', $a) }}" style="display:inline" onsubmit="return confirm('Удалить статью?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><x-icon name="trash" size="14"/></button></form>
      </td>
    </tr>
  @empty
    <tr><td colspan="4" class="muted" style="text-align:center;padding:40px">Статей нет</td></tr>
  @endforelse
  </tbody>
</table></div>
{{ $items->links() }}
@endsection
