<div class="page-hero"><div class="container">
  <div class="crumbs"><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a> / {{ __('site.account.title') }}</div>
  <h1>{{ auth()->user()->name }}</h1><p>{{ auth()->user()->email }}</p>
</div></div>
<div class="container" style="padding-top:32px">
  <div class="acc-nav">
    <a href="{{ route('account.index') }}" class="{{ request()->routeIs('account.index', 'account.requests.show') ? 'on' : '' }}"><x-icon name="file" size="16"/> {{ __('site.account.my_requests') }}</a>
    <a href="{{ route('account.requests.create') }}" class="{{ request()->routeIs('account.requests.create') ? 'on' : '' }}"><x-icon name="plus" size="16"/> {{ __('site.account.new_request') }}</a>
    <a href="{{ route('account.profile') }}" class="{{ request()->routeIs('account.profile') ? 'on' : '' }}"><x-icon name="user" size="16"/> {{ __('site.account.profile') }}</a>
    @if(auth()->user()->isStaff())<a href="{{ route('admin.dashboard') }}"><x-icon name="dashboard" size="16"/> {{ __('site.nav.admin') }}</a>@endif
    <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">@csrf<button class="btn btn-outline btn-sm"><x-icon name="logout" size="16"/> {{ __('site.nav.logout') }}</button></form>
  </div>
</div>
