@extends('layouts.admin')
@section('title', $item->exists ? 'Вопрос' : 'Новый вопрос')
@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.faqs.update', $item) : route('admin.faqs.store') }}" class="card grid" style="max-width:900px">
  @csrf @if($item->exists) @method('PUT') @endif
  @include('admin._lang-fields', ['fields' => [['name' => 'question', 'label' => 'Вопрос'], ['name' => 'answer', 'label' => 'Ответ', 'type' => 'textarea', 'rows' => 5]]])
  <div class="field" style="max-width:200px"><label>Порядок</label><input type="number" name="sort" value="{{ old('sort', $item->sort) }}"></div>
  <label class="check"><input type="checkbox" name="is_active" value="1" @checked($item->is_active)> Показывать на сайте</label>
  <div style="display:flex;gap:8px"><button class="btn btn-primary">Сохранить</button><a href="{{ route('admin.faqs.index') }}" class="btn">Отмена</a></div>
</form>
@endsection
