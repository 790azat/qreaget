@extends('layouts.admin')
@section('title', $item->exists ? 'Статья' : 'Новая статья')
@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.articles.update', $item) : route('admin.articles.store') }}" class="card grid" style="max-width:1000px" enctype="multipart/form-data">
  @csrf @if($item->exists) @method('PUT') @endif
  @include('admin._lang-fields', ['fields' => [['name' => 'title', 'label' => 'Заголовок'], ['name' => 'excerpt', 'label' => 'Краткое описание', 'type' => 'textarea', 'rows' => 2], ['name' => 'body', 'label' => 'Текст', 'type' => 'textarea', 'rows' => 12]]])
  <div class="field">
    <label>Обложка статьи</label>
    <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap">
      <img id="coverPreview" src="{{ $item->exists ? $item->cover_url : asset('images/articles/documents.jpg') }}" alt="" style="width:240px;aspect-ratio:16/9;object-fit:cover;border-radius:8px;border:1px solid #ddd">
      <div class="grid" style="flex:1;min-width:240px;gap:8px">
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" onchange="if(this.files[0])document.getElementById('coverPreview').src=URL.createObjectURL(this.files[0])">
        <small style="color:#777">JPG, PNG или WebP до 4 МБ. Лучше горизонтальное фото без текста. Если не загрузить — будет стандартная фотография.</small>
        <input type="text" name="image_url" value="{{ old('image_url', $item->image_url) }}" placeholder="или ссылка на картинку (https://…)">
        @if($item->image_url)<label class="check"><input type="checkbox" name="remove_image" value="1"> Убрать свою обложку (вернуть стандартную)</label>@endif
      </div>
    </div>
  </div>
  <div class="grid g3">
    <div class="field"><label>Slug</label><input name="slug" value="{{ old('slug', $item->slug) }}" placeholder="авто"></div>
    <div class="field"><label>Дата публикации</label><input type="date" name="published_at" value="{{ old('published_at', $item->published_at?->format('Y-m-d')) }}"></div>
  </div>
  <label class="check"><input type="checkbox" name="is_published" value="1" @checked($item->is_published)> Опубликована</label>
  <div style="display:flex;gap:8px"><button class="btn btn-primary">Сохранить</button><a href="{{ route('admin.articles.index') }}" class="btn">Отмена</a></div>
</form>
@endsection
