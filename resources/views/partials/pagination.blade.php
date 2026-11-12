@if ($paginator->hasPages())
<nav class="pager">
  @if ($paginator->onFirstPage())<span class="dis">‹</span>@else<a href="{{ $paginator->previousPageUrl() }}">‹</a>@endif
  @if(method_exists($paginator, 'getUrlRange'))
    @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
      @if ($page == $paginator->currentPage())<span class="cur">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
    @endforeach
  @endif
  @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">›</a>@else<span class="dis">›</span>@endif
</nav>
@endif
