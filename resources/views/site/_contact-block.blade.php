<div class="grid">
  <div>
    <span class="label">{{ __('site.contact.label') }}</span>
    <h2>{{ __('site.contact.title') }}</h2>
    <p class="muted">{{ __('site.contact.text') }}</p>
    <div class="cinfo">
      <div class="citem"><span class="ic"><x-icon name="pin" size="22"/></span><div><small>{{ __('site.contact.address') }}</small><span>{{ lsetting('address') }}</span></div></div>
      <div class="citem"><span class="ic"><x-icon name="phone" size="22"/></span><div><small>{{ __('site.contact.phone') }}</small><a href="{{ tel_href(setting('phone_1')) }}">{{ setting('phone_1') }}</a><br><a href="{{ tel_href(setting('phone_2')) }}">{{ setting('phone_2') }}</a></div></div>
      <div class="citem"><span class="ic"><x-icon name="mail" size="22"/></span><div><small>{{ __('site.contact.email') }}</small><a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></div></div>
      <div class="citem"><span class="ic"><x-icon name="clock" size="22"/></span><div><small>{{ __('site.contact.hours') }}</small><span>{{ lsetting('hours') }}</span></div></div>
      <div class="citem"><span class="ic"><x-icon name="globe" size="22"/></span><div><small>{{ __('site.contact.socials') }}</small>
        <div class="socials">
          @if(setting('instagram'))<a href="{{ setting('instagram') }}" target="_blank" rel="noopener" aria-label="Instagram"><x-icon name="instagram" size="18"/></a>@endif
          @if(setting('facebook'))<a href="{{ setting('facebook') }}" target="_blank" rel="noopener" aria-label="Facebook"><x-icon name="facebook" size="18"/></a>@endif
          @if(setting('telegram_link'))<a href="{{ setting('telegram_link') }}" target="_blank" rel="noopener" aria-label="Telegram"><x-icon name="telegram" size="18"/></a>@endif
        </div></div></div>
      <div class="map"><iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q={{ urlencode(setting('map_query')) }}&z=16&output=embed" title="Map"></iframe></div>
    </div>
  </div>
  <div>@include('partials.request-form', ['departments' => $departments])</div>
</div>
