@extends('layouts.site')
@section('content')
<section class="hero">
  <div class="container">
    <div>
      <span class="badge"><x-icon name="award" size="16"/> {{ __('site.hero.badge') }}</span>
      <h1>{{ lsetting('hero_title') ?: __('site.hero.title') }} <em>{{ lsetting('hero_title') ? '' : __('site.hero.title_em') }}</em></h1>
      <p class="lead">{{ lsetting('hero_text') ?: __('site.hero.text') }}</p>
      <div class="actions">
        <a href="#contact" class="btn btn-gold">{{ __('site.hero.cta') }} <x-icon name="arrow" size="18"/></a>
        <a href="#services" class="btn btn-ghost">{{ __('site.hero.cta2') }}</a>
      </div>
      <div class="tag"><x-icon name="shield" size="18"/> {{ __('site.hero.tag') }}</div>
    </div>
    <div class="hero-card">
      <img src="{{ asset('images/logo.png') }}" alt="{{ setting('site_name') }}">
      <ul>
        <li><x-icon name="pin" size="20"/> {{ lsetting('address') }}</li>
        <li><x-icon name="phone" size="20"/> <a href="{{ tel_href(setting('phone_1')) }}" style="color:inherit">{{ setting('phone_1') }}</a>&nbsp;·&nbsp;<a href="{{ tel_href(setting('phone_2')) }}" style="color:inherit">{{ setting('phone_2') }}</a></li>
        <li><x-icon name="mail" size="20"/> <a href="mailto:{{ setting('email') }}" style="color:inherit">{{ setting('email') }}</a></li>
        <li><x-icon name="clock" size="20"/> {{ lsetting('hours') }}</li>
      </ul>
    </div>
  </div>
</section>

<section class="principles">
  <div class="container">
    <div class="grid">
      @foreach(__('site.principles') as $i => $p)
        <div class="p"><x-icon :name="['scale','eye','lock','award'][$i]" size="28"/><div><b>{{ $p[0] }}</b><span>{{ $p[1] }}</span></div></div>
      @endforeach
    </div>
  </div>
</section>

<section class="about" id="about">
  <div class="container grid">
    <div class="reveal">
      <span class="label">{{ __('site.about.label') }}</span>
      <h2>{{ __('site.about.title') }}</h2>
      <p class="muted" style="font-size:1.07rem">{{ lsetting('about') ?: __('site.about.text') }}</p>
      <div class="stats">
        @foreach(__('site.about.stats') as $s)<div class="stat"><b>{{ $s[0] }}</b><span>{{ $s[1] }}</span></div>@endforeach
      </div>
    </div>
    <div class="about-visual reveal"><img src="{{ asset('images/logo.jpg') }}" alt=""></div>
  </div>
</section>

<section class="services" id="services">
  <div class="container">
    <div class="section-head reveal">
      <span class="label">{{ __('site.services.label') }}</span>
      <h2>{{ __('site.services.title') }}</h2>
      <p class="muted">{{ __('site.services.text') }}</p>
    </div>
    <div class="svc-grid">
      @foreach($departments as $i => $d)
        <div class="svc reveal">
          <span class="num n">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="ic"><x-icon :name="$d->icon" size="26"/></span>
          <h3>{{ $d->t('name') }}</h3>
          <p>{{ $d->t('description') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="audience">
  <div class="container">
    <div class="section-head center reveal">
      <span class="label">{{ __('site.audience.label') }}</span>
      <h2>{{ __('site.audience.title') }}</h2>
    </div>
    <div class="aud-grid">
      @foreach(__('site.audience.items') as $i => $a)
        <div class="aud reveal"><x-icon :name="['user','briefcase','factory','ship'][$i]" size="34"/><h3>{{ $a[0] }}</h3><p>{{ $a[1] }}</p></div>
      @endforeach
    </div>
  </div>
</section>

<section class="process" id="process">
  <div class="container">
    <div class="section-head reveal">
      <span class="label">{{ __('site.process.label') }}</span>
      <h2>{{ __('site.process.title') }}</h2>
    </div>
    <div class="steps">
      @foreach(__('site.process.steps') as $s)
        <div class="step reveal"><b>{{ $s[0] }}</b><span>{{ $s[1] }}</span></div>
      @endforeach
    </div>
  </div>
</section>

<section class="contact" id="contact">
  <div class="container">
    @include('site._contact-block')
  </div>
</section>
@endsection
