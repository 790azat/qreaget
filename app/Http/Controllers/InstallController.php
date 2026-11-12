<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * One-click installer for hosts without SSH (Vercel, shared hosting).
 * Set INSTALL_KEY in .env and open /install/{INSTALL_KEY}.
 */
class InstallController extends Controller
{
    public function __invoke(string $key)
    {
        $expected = (string) config('qreaget.install_key');
        abort_if($expected === '' || ! hash_equals($expected, $key), 404);

        @set_time_limit(300);
        // Neon's pooled endpoint (PgBouncer) breaks schema migrations inside
        // transactions; run the installer over the direct connection instead.
        if ($direct = env('DATABASE_URL_UNPOOLED') ?: env('POSTGRES_URL_NON_POOLING')) {
            config(['database.connections.pgsql.url' => $direct]);
            DB::purge('pgsql');
        }

        $out = [];
        try {
            Artisan::call('migrate', ['--force' => true]);
            $out[] = Artisan::output();
            Artisan::call('db:seed', ['--force' => true]);
            $out[] = Artisan::output();
        } catch (\Throwable $e) {
            // Only reachable with the secret install key: show why it failed.
            return response('<pre>Install failed: '.e(get_class($e).': '.$e->getMessage()).'</pre>', 500);
        }

        return response('<pre>'.e(implode("\n", $out))."\nDone. Remove INSTALL_KEY from the environment now.</pre>");
    }
}
