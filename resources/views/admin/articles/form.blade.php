@extends('layouts.admin')
@section('title', $item->exists ? 'Статья' : 'Новая статья')
@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.articles.update', $item) : route('admin.articles.store') }}" class="card grid" style="max-width:1000px">
  @csrf @if($item->exists) @method('PUT') @endif
  @include('admin._lang-fields', ['fields' => [['name' => 'title', 'label' => 'Заголовок'], ['name' => 'excerpt', 'label' => 'Краткое описание', 'type' => 'textarea', 'rows' => 2], ['name' => 'body', 'label' => 'Текст', 'type' => 'textarea', 'rows' => 12]]])
  <div class="grid g3">
    <div class="field"><label>Slug</label><input name="slug" value="{{ old('slug', $item->slug) }}" placeholder="авто"></div>
    <div class="field"><label>Дата публикации</label><input type="date" name="published_at" value="{{ old('published_at', $item->published_at?->format('Y-m-d')) }}"></div>
    <div class="field"><label>Ссылка на картинку (URL)</label><input type="url" name="image_url" value="{{ old('image_url', $item->image_url) }}"></div>
  </div>
  <label class="check"><input type="checkbox" name="is_published" value="1" @checked($item->is_published)> Опубликована</label>
  <div style="display:flex;gap:8px"><button class="btn btn-primary">Сохранить</button><a href="{{ route('admin.articles.index') }}" class="btn">Отмена</a></div>
</form>
@endsection
