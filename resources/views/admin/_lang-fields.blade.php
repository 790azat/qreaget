{{-- $fields = [['name' => 'title', 'label' => 'Заголовок', 'type' => 'text|textarea', 'rows' => 4]] --}}
<div data-lang-tabs>
  <div class="lang-tabs">
    @foreach(['hy' => 'Հայերեն *', 'ru' => 'Русский', 'en' => 'English'] as $l => $label)
      <button class="{{ $loop->first ? 'on' : '' }}">{{ $label }}</button>
    @endforeach
  </div>
  @foreach(['hy', 'ru', 'en'] as $l)
    <div class="lang-pane {{ $loop->first ? 'on' : '' }}">
      @foreach($fields as $f)
        <div class="field"><label>{{ $f['label'] }} ({{ strtoupper($l) }})</label>
          @if(($f['type'] ?? 'text') === 'textarea')
            <textarea name="{{ $f['name'] }}_{{ $l }}" rows="{{ $f['rows'] ?? 4 }}">{{ old($f['name'].'_'.$l, $item->{$f['name'].'_'.$l}) }}</textarea>
          @else
            <input type="text" name="{{ $f['name'] }}_{{ $l }}" value="{{ old($f['name'].'_'.$l, $item->{$f['name'].'_'.$l}) }}">
          @endif
        </div>
      @endforeach
    </div>
  @endforeach
</div>
