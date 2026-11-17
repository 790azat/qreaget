<a href="{{ route('article', $a->slug) }}" class="art reveal">
  <div class="cover" style="background-image:url('{{ $a->cover_url }}')"></div>
  <div class="body">
    <time>{{ optional($a->published_at)->format('d.m.Y') }}</time>
    <h3>{{ $a->t('title') }}</h3>
    <p>{{ $a->t('excerpt') }}</p>
    <span class="more" style="font-size:.85rem;font-weight:600;color:var(--g600)">{{ __('site.articles.read') }} →</span>
  </div>
</a>
