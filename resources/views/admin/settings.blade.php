@extends('layouts.admin')
@section('title', 'Настройки и Telegram')
@section('content')
@php $v = fn ($k) => old($k, \App\Models\Setting::get($k, config("qreaget.defaults.$k"))); @endphp
<div class="grid g-main">
<form method="POST" action="{{ route('admin.settings.update') }}" class="card">
  @csrf @method('PUT')
  <div class="tabs">
    <button type="button" class="on" data-t="0">Контакты</button>
    <button type="button" data-t="1">Тексты главной</button>
    <button type="button" data-t="2">Telegram</button>
    <button type="button" data-t="3">Функции и SEO</button>
  </div>
  <div class="tab on">
    <div class="grid g2">
      <div class="field"><label>Название</label><input name="site_name" value="{{ $v('site_name') }}"></div>
      <div class="field"><label>Email</label><input type="email" name="email" value="{{ $v('email') }}"></div>
      <div class="field"><label>Телефон 1</label><input name="phone_1" value="{{ $v('phone_1') }}"></div>
      <div class="field"><label>Телефон 2</label><input name="phone_2" value="{{ $v('phone_2') }}"></div>
      @foreach(['hy','ru','en'] as $l)
        <div class="field"><label>Адрес ({{ strtoupper($l) }})</label><input name="address_{{ $l }}" value="{{ $v('address_'.$l) }}"></div>
        <div class="field"><label>Часы работы ({{ strtoupper($l) }})</label><input name="hours_{{ $l }}" value="{{ $v('hours_'.$l) }}"></div>
      @endforeach
      <div class="field"><label>Instagram</label><input type="url" name="instagram" value="{{ $v('instagram') }}"></div>
      <div class="field"><label>Facebook</label><input type="url" name="facebook" value="{{ $v('facebook') }}"></div>
      <div class="field"><label>Telegram-канал (ссылка)</label><input name="telegram_link" value="{{ $v('telegram_link') }}"></div>
      <div class="field"><label>Адрес для карты Google</label><input name="map_query" value="{{ $v('map_query') }}"></div>
    </div>
  </div>
  <div class="tab">
    <p class="muted">Оставьте поле пустым, чтобы использовать стандартный текст.</p>
    @foreach(['hy','ru','en'] as $l)
      <div class="field"><label>Заголовок ({{ strtoupper($l) }})</label><input name="hero_title_{{ $l }}" value="{{ \App\Models\Setting::get('hero_title_'.$l) }}"></div>
      <div class="field"><label>Подзаголовок ({{ strtoupper($l) }})</label><textarea name="hero_text_{{ $l }}" rows="2">{{ \App\Models\Setting::get('hero_text_'.$l) }}</textarea></div>
      <div class="field"><label>Текст «О центре» ({{ strtoupper($l) }})</label><textarea name="about_{{ $l }}" rows="3">{{ \App\Models\Setting::get('about_'.$l) }}</textarea></div>
    @endforeach
  </div>
  <div class="tab">
    <div class="field"><label>Токен бота (от @BotFather)</label><input name="telegram_bot_token" value="{{ $v('telegram_bot_token') }}" placeholder="123456:ABC..." autocomplete="off"></div>
    <div class="field"><label>Chat ID администратора или группы</label><input name="telegram_chat_id" value="{{ $v('telegram_chat_id') }}" placeholder="-100123... или 123456789"></div>
    <label class="check"><input type="checkbox" name="notify_requests" value="1" @checked($v('notify_requests') === '1')> Присылать новые заявки и регистрации в Telegram</label>
  </div>
  <div class="tab">
    <label class="check"><input type="checkbox" name="chat_enabled" value="1" @checked($v('chat_enabled') === '1')> Онлайн-чат на сайте</label>
    <label class="check"><input type="checkbox" name="registration_enabled" value="1" @checked($v('registration_enabled') === '1')> Регистрация клиентов</label>
    <div class="field"><label>SEO описание сайта</label><textarea name="meta_description" rows="2">{{ \App\Models\Setting::get('meta_description') }}</textarea></div>
    <div class="field"><label>Код аналитики (Google Analytics / Яндекс.Метрика) — вставляется в &lt;head&gt;</label><textarea name="analytics_code" rows="4">{{ \App\Models\Setting::get('analytics_code') }}</textarea></div>
  </div>
  <button class="btn btn-primary" style="margin-top:16px">Сохранить настройки</button>
</form>
<div class="grid">
  <div class="card">
    <h2>Подключение Telegram-бота</h2>
    <ol style="padding-left:18px;margin:0 0 12px;display:grid;gap:6px">
      <li>Создайте бота в <a href="https://t.me/BotFather" target="_blank">@BotFather</a> командой <code>/newbot</code> и вставьте токен во вкладку «Telegram».</li>
      <li>Сохраните и нажмите «Установить webhook».</li>
      <li>Напишите боту <code>/start</code> (или добавьте его в группу и напишите <code>/id</code>) — он пришлёт Chat ID.</li>
      <li>Вставьте Chat ID, сохраните и нажмите «Тест».</li>
    </ol>
    <p class="muted" style="font-size:.82rem">Ответ посетителю: ответьте (Reply) на сообщение бота. Команды: <code>/chats</code>, <code>/requests</code>, <code>/r ID текст</code>.</p>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <form method="POST" action="{{ route('admin.settings.webhook') }}">@csrf<button class="btn btn-gold">Установить webhook</button></form>
      <form method="POST" action="{{ route('admin.settings.test') }}">@csrf<button class="btn">Тест</button></form>
    </div>
    <p class="muted" style="font-size:.78rem;margin-top:12px">Webhook URL:<br><code>{{ $webhookUrl }}</code></p>
    @if($telegramInfo)
      <p style="font-size:.8rem;margin:0">Статус: @if(($telegramInfo['result']['url'] ?? '') === $webhookUrl)<span class="pill pill-green">webhook установлен</span>@else<span class="pill pill-amber">webhook не установлен</span>@endif
      @if(!empty($telegramInfo['result']['last_error_message']))<br><span class="muted">Последняя ошибка: {{ $telegramInfo['result']['last_error_message'] }}</span>@endif</p>
    @endif
  </div>
</div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.tabs button').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('.tabs button').forEach(x=>x.classList.remove('on'));document.querySelectorAll('.tab').forEach(x=>x.classList.remove('on'));
  b.classList.add('on');document.querySelectorAll('.tab')[+b.dataset.t].classList.add('on')}));
</script>
@endpush
