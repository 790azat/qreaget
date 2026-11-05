<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex">
<title>@yield('title', 'Панель') · {{ setting('site_name') }} Admin</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=3">
</head>
<body>
@php $u = auth()->user(); $unread = \App\Models\ChatSession::where('unread_admin', true)->count(); $newReq = \App\Models\ServiceRequest::where('status', 'new')->count(); @endphp
<div class="layout">
  <aside class="side" id="side">
    <a href="{{ route('admin.dashboard') }}" class="brand"><img src="{{ asset('images/logo.png') }}" alt=""><span><b>{{ setting('site_name') }}</b><small>Панель управления</small></span></a>
    <nav>
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}"><x-icon name="dashboard" size="18"/> Обзор</a>
      <a href="{{ route('admin.requests.index') }}" class="{{ request()->routeIs('admin.requests.*') ? 'on' : '' }}"><x-icon name="file" size="18"/> Заявки @if($newReq)<span class="badge">{{ $newReq }}</span>@endif</a>
      <a href="{{ route('admin.chats.index') }}" class="{{ request()->routeIs('admin.chats.*') ? 'on' : '' }}"><x-icon name="chat" size="18"/> Онлайн-чат <span class="badge" id="chatBadge" @if(!$unread) style="display:none" @endif>{{ $unread }}</span></a>
      @if($u->isAdmin())
        <div class="sep">Контент</div>
        <a href="{{ route('admin.departments.index') }}" class="{{ request()->routeIs('admin.departments.*') ? 'on' : '' }}"><x-icon name="scale" size="18"/> Отделы / услуги</a>
        <a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.*') ? 'on' : '' }}"><x-icon name="edit" size="18"/> Статьи</a>
        <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'on' : '' }}"><x-icon name="help" size="18"/> Вопросы (FAQ)</a>
        <div class="sep">Система</div>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'on' : '' }}"><x-icon name="users" size="18"/> Пользователи</a>
        <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'on' : '' }}"><x-icon name="settings" size="18"/> Настройки и Telegram</a>
        <a href="{{ route('admin.activity') }}" class="{{ request()->routeIs('admin.activity') ? 'on' : '' }}"><x-icon name="activity" size="18"/> Журнал действий</a>
      @endif
    </nav>
    <div class="foot">
      <div style="padding:6px 8px;color:#fff">{{ $u->name }} <small class="muted">({{ $u->role }})</small></div>
      <a href="{{ route('home') }}" target="_blank"><x-icon name="globe" size="16"/> Открыть сайт</a>
      <form method="POST" action="{{ route('logout') }}">@csrf<button><x-icon name="logout" size="16"/> Выйти</button></form>
    </div>
  </aside>
  <main class="main">
    <div class="top">
      <button class="btn mobile-toggle" onclick="document.getElementById('side').classList.toggle('open')"><x-icon name="menu" size="18"/></button>
      <h1>@yield('title', 'Панель')</h1>
      <div class="actions">@yield('actions')</div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert alert-error">{{ implode(' ', $errors->all()) }}</div>@endif
    @yield('content')
  </main>
</div>
<script>
// Language tabs for multilingual forms
document.querySelectorAll('[data-lang-tabs]').forEach(w=>{
  const btns=w.querySelectorAll('.lang-tabs button'),panes=w.querySelectorAll('.lang-pane');
  btns.forEach((b,i)=>b.addEventListener('click',e=>{e.preventDefault();btns.forEach(x=>x.classList.remove('on'));panes.forEach(x=>x.classList.remove('on'));b.classList.add('on');panes[i].classList.add('on')}));
});
// Unread chats badge + sound
(function(){
  let last={{ $unread }};const badge=document.getElementById('chatBadge');
  setInterval(async()=>{try{const r=await fetch(@json(route('admin.chats.unread')),{headers:{'Accept':'application/json'}});const j=await r.json();
    badge.textContent=j.count;badge.style.display=j.count?'':'none';
    if(j.count>last){try{const a=new AudioContext(),o=a.createOscillator();o.frequency.value=880;o.connect(a.destination);o.start();o.stop(a.currentTime+.15)}catch(e){};document.title='('+j.count+') '+document.title.replace(/^\(\d+\) /,'')}
    last=j.count;}catch(e){}},15000);
})();
</script>
@stack('scripts')
</body>
</html>
