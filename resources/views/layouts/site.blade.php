<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@hasSection('title')@yield('title') — @endif{{ setting('site_name') }} · {{ __('site.tagline') }}</title>
<meta name="description" content="@yield('description', setting('meta_description', __('site.hero.text')))">
<meta property="og:title" content="{{ setting('site_name') }} — {{ __('site.tagline') }}">
<meta property="og:description" content="{{ __('site.hero.text') }}">
<meta property="og:image" content="{{ asset('images/logo.jpg') }}">
<meta name="theme-color" content="#16301f">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;500;600;700&family=Noto+Serif+Armenian:wght@500;600;700&family=Noto+Sans:wght@400;500;600;700&family=Noto+Serif:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v=5">
</head>
<body>
<div class="topbar">
  <div class="container">
    <div class="tb-left">
      <a href="{{ tel_href(setting('phone_1')) }}"><x-icon name="phone" size="14"/> {{ setting('phone_1') }}</a>
      <a href="{{ tel_href(setting('phone_2')) }}"><x-icon name="phone" size="14"/> {{ setting('phone_2') }}</a>
      <a href="mailto:{{ setting('email') }}"><x-icon name="mail" size="14"/> {{ setting('email') }}</a>
    </div>
    <div class="tb-right">
      <a href="{{ route('contact') }}"><x-icon name="pin" size="14"/> {{ lsetting('address') }}</a>
      @if(setting('instagram'))<a href="{{ setting('instagram') }}" target="_blank" rel="noopener" aria-label="Instagram"><x-icon name="instagram" size="15"/></a>@endif
      @if(setting('facebook'))<a href="{{ setting('facebook') }}" target="_blank" rel="noopener" aria-label="Facebook"><x-icon name="facebook" size="15"/></a>@endif
    </div>
  </div>
</div>
<header class="header">
  <div class="container">
    <a href="{{ route('home') }}" class="brand">
      <img src="{{ asset('images/logo.png') }}" alt="{{ setting('site_name') }}">
      <span><b>{{ setting('site_name') }}</b><small>{{ __('site.tagline') }}</small></span>
    </a>
    <button class="burger" aria-label="Menu" onclick="document.querySelector('.nav').classList.toggle('open')"><x-icon name="menu" size="28"/></button>
    <nav class="nav">
      <a href="{{ route('home') }}#about">{{ __('site.nav.about') }}</a>
      <a href="{{ route('services') }}" class="{{ request()->routeIs('service*') ? 'active' : '' }}">{{ __('site.nav.services') }}</a>
      <a href="{{ route('home') }}#process">{{ __('site.nav.process') }}</a>
      <a href="{{ route('articles') }}" class="{{ request()->routeIs('article*') ? 'active' : '' }}">{{ __('site.nav.articles') }}</a>
      <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('site.nav.contact') }}</a>
      @auth
        <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit()"><x-icon name="logout" size="16"/> {{ __('site.nav.logout') }}</a>
        <form id="logout-form" method="POST" action="{{ route('logout') }}" hidden>@csrf</form>
      @else
        <a href="{{ route('login') }}"><x-icon name="user" size="16"/> {{ __('site.nav.login') }}</a>
      @endauth
    </nav>
    <div class="header-actions">
      <div class="lang">
        @foreach(config('qreaget.locales') as $code => $label)
          <a href="{{ route('lang', $code) }}" class="{{ app()->getLocale() === $code ? 'on' : '' }}">{{ $label }}</a>
        @endforeach
      </div>
      <a href="{{ route('contact') }}#form" class="btn btn-primary btn-sm btn-request">{{ __('site.nav.request') }}</a>
    </div>
  </div>
</header>

@if(session('success'))<div class="alert alert-success flash"><x-icon name="check" size="18"/> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error flash">{{ session('error') }}</div>@endif

<main>@yield('content')</main>

<footer class="footer">
  <div class="container">
    <div class="grid">
      <div>
        <a href="{{ route('home') }}" class="brand" style="margin-bottom:16px"><img src="{{ asset('images/logo.png') }}" alt=""><span><b>{{ setting('site_name') }}</b></span></a>
        <p>{{ __('site.tagline') }}. {{ __('site.hero.tag') }}.</p>
        <div class="socials">
          @if(setting('instagram'))<a href="{{ setting('instagram') }}" target="_blank" rel="noopener" aria-label="Instagram"><x-icon name="instagram" size="18"/></a>@endif
          @if(setting('facebook'))<a href="{{ setting('facebook') }}" target="_blank" rel="noopener" aria-label="Facebook"><x-icon name="facebook" size="18"/></a>@endif
        </div>
      </div>
      <div>
        <h4>{{ __('site.nav.services') }}</h4>
        @foreach(($footerDepartments ?? collect())->take(6) as $d)
          <a href="{{ route('service', $d->slug) }}">{{ $d->t('name') }}</a>
        @endforeach
        <a href="{{ route('services') }}">{{ __('site.services.all') }} →</a>
      </div>
      <div>
        <h4>{{ __('site.footer.links') }}</h4>
        <a href="{{ route('home') }}#about">{{ __('site.nav.about') }}</a>
        <a href="{{ route('home') }}#process">{{ __('site.nav.process') }}</a>
        <a href="{{ route('services') }}">{{ __('site.nav.services') }}</a>
        <a href="{{ route('articles') }}">{{ __('site.nav.articles') }}</a>
        <a href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a>
        @if(setting('registration_enabled') === '1')<a href="{{ route('register') }}">{{ __('site.nav.register') }}</a>@endif
        @guest<a href="{{ route('login') }}">{{ __('site.nav.login') }}</a>@endguest
      </div>
      <div>
        <h4>{{ __('site.nav.contact') }}</h4>
        <a href="{{ tel_href(setting('phone_1')) }}">{{ setting('phone_1') }}</a>
        <a href="{{ tel_href(setting('phone_2')) }}">{{ setting('phone_2') }}</a>
        <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>
        <span>{{ lsetting('address') }}</span><br>
        <span>{{ lsetting('hours') }}</span>
      </div>
    </div>
    <div class="bottom">
      <span>© {{ date('Y') }} «{{ setting('site_name') }}» {{ __('site.tagline') }}. {{ __('site.footer.rights') }}</span>
      <span>{{ lsetting('address') }}</span>
    </div>
  </div>
</footer>

<script>
document.querySelectorAll('.reveal').forEach(el=>{
  if(!('IntersectionObserver' in window)){el.classList.add('in');return}
  new IntersectionObserver((es,o)=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');o.unobserve(e.target)}}),{threshold:.12}).observe(el)
});
</script>
</body>
</html>
