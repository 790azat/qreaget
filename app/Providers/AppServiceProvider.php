<?php

namespace App\Providers;

use App\Models\Department;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
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

        $this->autoInstall();

        RateLimiter::for('forms', fn (Request $r) => Limit::perMinute(8)->by('forms|'.$r->path().'|'.$r->ip()));

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

    private function autoInstall(): void
    {
        if (! config('app.auto_install') || config('database.default') !== 'sqlite') {
            return;
        }

        $path = config('database.connections.sqlite.database');
        if (! $path || $path === ':memory:' || file_exists($path)) {
            return;
        }

        @mkdir(dirname($path), 0777, true);
        touch($path);
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
    }
}
