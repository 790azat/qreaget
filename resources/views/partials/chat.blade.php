<button class="chat-fab" id="chatFab" aria-label="{{ __('site.chat.open') }}"><span class="dot"></span><x-icon name="chat" size="28"/></button>
<div class="chat-box" id="chatBox" role="dialog" aria-label="{{ __('site.chat.title') }}">
  <div class="chat-head">
    <img src="{{ asset('images/logo.png') }}" alt="">
    <div><b>{{ __('site.chat.title') }}</b><small>{{ __('site.chat.subtitle') }}</small></div>
    <button id="chatClose" aria-label="Close"><x-icon name="x" size="22"/></button>
  </div>
  <div class="chat-body" id="chatBody">
    <div class="msg system">{{ __('site.chat.intro') }}</div>
  </div>
  <div class="chat-intro" id="chatIntro">
    <input id="chatName" placeholder="{{ __('site.chat.name') }}" maxlength="100" value="{{ auth()->user()?->name }}">
    <input id="chatContact" placeholder="{{ __('site.chat.contact') }}" maxlength="120" value="{{ auth()->user()?->phone }}">
  </div>
  <form class="chat-foot" id="chatForm">
    <textarea id="chatInput" placeholder="{{ __('site.chat.placeholder') }}" maxlength="2000" rows="1"></textarea>
    <button type="submit" id="chatSend" aria-label="{{ __('site.chat.send') }}"><x-icon name="send" size="20"/></button>
  </form>
</div>
<script>
window.QCHAT = {
  start: @json(route('chat.start')), send: @json(route('chat.send')), poll: @json(route('chat.poll')),
  csrf: @json(csrf_token())
};
</script>
<script src="{{ asset('js/chat.js') }}?v=5" defer></script>
