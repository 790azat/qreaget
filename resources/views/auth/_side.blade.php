<div class="side">
  <img src="{{ asset('images/logo.png') }}" alt="">
  <h2>{{ setting('site_name') }}</h2>
  <p>{{ __('site.tagline') }}</p>
  <ul>@foreach(__('site.auth.benefits') as $b)<li><x-icon name="check" size="18"/> {{ $b }}</li>@endforeach</ul>
</div>
