@extends('layouts.admin')
@section('title', 'Чат #'.$chat->id.' · '.$chat->displayName())
@section('actions')
  <a href="{{ route('admin.chats.index') }}" class="btn">← Все чаты</a>
  <form method="POST" action="{{ route('admin.chats.toggle', $chat) }}">@csrf<button class="btn">{{ $chat->status === 'open' ? 'Закрыть чат' : 'Открыть снова' }}</button></form>
  @if(auth()->user()->isAdmin())
    <form method="POST" action="{{ route('admin.chats.destroy', $chat) }}" onsubmit="return confirm('Удалить чат?')">@csrf @method('DELETE')<button class="btn btn-danger"><x-icon name="trash" size="16"/></button></form>
  @endif
@endsection
@section('content')
<div class="grid g-main">
  <div class="chat-wrap">
    <div class="chat-msgs" id="msgs">
      @foreach($chat->messages as $m)
        <div class="msg {{ $m->sender }}" data-id="{{ $m->id }}">@if($m->author && $m->sender !== 'visitor')<span class="who">{{ $m->author }}</span>@endif{{ $m->body }}<small>{{ $m->created_at->format('d.m H:i') }}</small></div>
      @endforeach
    </div>
    <form class="chat-form" id="replyForm">
      <textarea id="replyText" placeholder="Ответ посетителю… (Enter — отправить, Shift+Enter — новая строка)" required></textarea>
      <button class="btn btn-primary"><x-icon name="send" size="16"/> Отправить</button>
    </form>
  </div>
  <div class="card">
    <h2>Посетитель</h2>
    <dl style="display:grid;grid-template-columns:110px 1fr;gap:8px;margin:0">
      <dt class="muted">Имя</dt><dd style="margin:0">{{ $chat->name ?: '—' }}</dd>
      <dt class="muted">Контакт</dt><dd style="margin:0">{{ $chat->contact ?: '—' }}</dd>
      <dt class="muted">Аккаунт</dt><dd style="margin:0">@if($chat->user)<a href="{{ route('admin.users.edit', $chat->user) }}">{{ $chat->user->email }}</a>@else гость @endif</dd>
      <dt class="muted">Страница</dt><dd style="margin:0;word-break:break-all">{{ $chat->page_url ?: '—' }}</dd>
      <dt class="muted">IP</dt><dd style="margin:0">{{ $chat->ip }}</dd>
      <dt class="muted">Начат</dt><dd style="margin:0">{{ $chat->created_at->format('d.m.Y H:i') }}</dd>
    </dl>
    <p class="muted" style="font-size:.82rem;margin-top:16px">Отвечать можно здесь или прямо в Telegram: ответьте (Reply) на сообщение бота, либо напишите <code>/r {{ $chat->id }} текст</code>.</p>
    <a class="btn" href="{{ route('admin.requests.create') }}">Создать заявку из чата</a>
  </div>
</div>
@endsection
@push('scripts')
<script>
(function(){
  const box=document.getElementById('msgs'),form=document.getElementById('replyForm'),ta=document.getElementById('replyText');
  const csrf=document.querySelector('meta[name=csrf-token]').content;
  let last=Math.max(0,...[...box.querySelectorAll('[data-id]')].map(e=>+e.dataset.id));
  box.scrollTop=box.scrollHeight;
  function esc(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML}
  function add(m){if(box.querySelector('[data-id="'+m.id+'"]'))return;last=Math.max(last,m.id);
    const el=document.createElement('div');el.className='msg '+m.sender;el.dataset.id=m.id;
    el.innerHTML=(m.author&&m.sender!=='visitor'?'<span class="who">'+esc(m.author)+'</span>':'')+esc(m.body)+'<small>'+m.time+'</small>';
    box.appendChild(el);box.scrollTop=box.scrollHeight}
  async function poll(){try{const r=await fetch(@json(route('admin.chats.poll', $chat))+'?seen=1&after='+last,{headers:{Accept:'application/json'}});(await r.json()).messages.forEach(add)}catch(e){}setTimeout(poll,3000)}
  setTimeout(poll,3000);
  ta.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();form.requestSubmit()}});
  form.addEventListener('submit',async e=>{e.preventDefault();const body=ta.value.trim();if(!body)return;ta.value='';
    const r=await fetch(@json(route('admin.chats.reply', $chat)),{method:'POST',headers:{'Content-Type':'application/json',Accept:'application/json','X-CSRF-TOKEN':csrf},body:JSON.stringify({body})});
    if(r.ok)add((await r.json()).message);else{ta.value=body;alert('Ошибка отправки')}});
})();
</script>
@endpush
