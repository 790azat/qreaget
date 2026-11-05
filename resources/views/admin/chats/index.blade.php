@extends('layouts.admin')
@section('title', 'Онлайн-чат')
@section('content')
@unless($telegram)
  <div class="alert alert-error">Telegram-бот не подключён — сообщения из чата не пересылаются в Telegram. <a href="{{ route('admin.settings.edit') }}">Настроить →</a></div>
@endunless
<form class="filters card" method="GET">
  <div class="field"><label>Поиск</label><input name="q" value="{{ request('q') }}" placeholder="Имя или контакт"></div>
  <div class="field"><label>Статус</label><select name="status"><option value="">Все</option><option value="open" @selected(request('status') === 'open')>Открытые</option><option value="closed" @selected(request('status') === 'closed')>Закрытые</option></select></div>
  <button class="btn btn-primary">Найти</button>
</form>
<div class="table-wrap" style="margin-top:12px"><table class="t">
  <thead><tr><th>#</th><th>Посетитель</th><th>Последнее сообщение</th><th>Сообщений</th><th>Статус</th><th>Активность</th></tr></thead>
  <tbody>
  @forelse($items as $c)
    <tr class="{{ $c->unread_admin ? 'unread' : '' }}">
      <td><a href="{{ route('admin.chats.show', $c) }}"><b>#{{ $c->id }}</b></a></td>
      <td><a href="{{ route('admin.chats.show', $c) }}" style="color:inherit"><b>{{ $c->displayName() }}</b></a><br><small class="muted">{{ $c->contact }}</small></td>
      <td style="max-width:380px"><span class="muted">{{ \Illuminate\Support\Str::limit($c->lastMessage?->body, 90) }}</span></td>
      <td>{{ $c->messages_count }}</td>
      <td>@if($c->unread_admin)<span class="pill pill-red">Новое</span>@elseif($c->status === 'open')<span class="pill pill-green">Открыт</span>@else<span class="pill pill-gray">Закрыт</span>@endif</td>
      <td class="muted">{{ $c->last_message_at?->diffForHumans() ?? $c->created_at->diffForHumans() }}</td>
    </tr>
  @empty
    <tr><td colspan="6" class="muted" style="text-align:center;padding:40px">Чатов пока нет</td></tr>
  @endforelse
  </tbody>
</table></div>
{{ $items->links() }}
@endsection
@push('scripts')<script>setTimeout(()=>location.reload(),30000)</script>@endpush
