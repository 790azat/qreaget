{{-- "Ask your question" banner. Pass ['compact' => true] for narrow columns. --}}
<div class="cta-band {{ !empty($compact) ? 'compact' : '' }} {{ $class ?? '' }}" @isset($style) style="{{ $style }}" @endisset>
  <div class="cta-text">
    <span class="label">{{ __('site.contact.cta_label') }}</span>
    <h2>{{ __('site.contact.title') }}</h2>
    <p>{{ __('site.contact.cta_text') }}</p>
  </div>
  <div class="cta-actions">
    <a href="{{ route('contact') }}#form" class="btn btn-primary">{{ __('site.nav.request') }}</a>
    @if(setting('phone_1'))<a href="{{ tel_href(setting('phone_1')) }}" class="btn btn-outline"><x-icon name="phone" size="18"/> {{ setting('phone_1') }}</a>@endif
  </div>
</div>
