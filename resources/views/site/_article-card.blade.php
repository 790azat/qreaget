<a href="{{ route('article', $a->slug) }}" class="art reveal">
  <div class="cover" @if($a->image_url) style="background-image:url('{{ $a->image_url }}')" @endif>@unless($a->image_url)<x-icon name="file" size="46"/>@endunless</div>
  <div class="body">
    <time>{{ optional($a->published_at)->format('d.m.Y') }}</time>
    <h3>{{ $a->t('title') }}</h3>
    <p>{{ $a->t('excerpt') }}</p>
    <span class="more" style="font-size:.85rem;font-weight:600;color:var(--g600)">{{ __('site.articles.read') }} →</span>
  </div>
</a>
