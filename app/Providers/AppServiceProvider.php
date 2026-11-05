<?php

namespace App\Providers;

use App\Models\Department;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }

        // Separate counters per form so chat polling never blocks registration etc.
        RateLimiter::for('chat', fn (Request $r) => Limit::perMinute(90)->by('chat|'.$r->ip()));
        RateLimiter::for('forms', fn (Request $r) => Limit::perMinute(8)->by('forms|'.$r->path().'|'.$r->ip()));
        RateLimiter::for('login', fn (Request $r) => Limit::perMinute(10)->by('login|'.$r->ip().'|'.strtolower((string) $r->input('email'))));

        Paginator::defaultView('partials.pagination');
        Paginator::defaultSimpleView('partials.pagination');

        View::composer('layouts.site', function ($view) {
            static $deps = null;
            if ($deps === null) {
                try {
                    $deps = Department::active()->get();
                } catch (\Throwable) {
                    $deps = collect();
                }
            }
            $view->with('footerDepartments', $deps);
        });
    }
}
