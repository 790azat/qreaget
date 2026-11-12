<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

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
