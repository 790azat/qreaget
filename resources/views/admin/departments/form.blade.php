@extends('layouts.admin')
@section('title', $item->exists ? 'Отдел: '.$item->name_ru : 'Новый отдел')
@section('content')
@php $icons = ['building','dna','flask','fingerprint','brain','pen','landmark','car','calculator','box','scale','shield','eye','file','award','briefcase']; @endphp
<form method="POST" action="{{ $item->exists ? route('admin.departments.update', $item) : route('admin.departments.store') }}" class="card grid" style="max-width:900px">
  @csrf @if($item->exists) @method('PUT') @endif
  @include('admin._lang-fields', ['fields' => [['name' => 'name', 'label' => 'Название'], ['name' => 'description', 'label' => 'Описание', 'type' => 'textarea', 'rows' => 6]]])
  <div class="grid g3">
    <div class="field"><label>Адрес страницы (slug)</label><input name="slug" value="{{ old('slug', $item->slug) }}" placeholder="авто"></div>
    <div class="field"><label>Порядок</label><input type="number" name="sort" value="{{ old('sort', $item->sort) }}"></div>
    <div class="field"><label>Иконка</label><select name="icon">@foreach($icons as $i)<option value="{{ $i }}" @selected($item->icon === $i)>{{ $i }}</option>@endforeach</select></div>
  </div>
  <label class="check"><input type="checkbox" name="is_active" value="1" @checked($item->is_active)> Показывать на сайте</label>
  <div style="display:flex;gap:8px"><button class="btn btn-primary">Сохранить</button><a href="{{ route('admin.departments.index') }}" class="btn">Отмена</a></div>
</form>
@endsection
