<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default ?? config("qreaget.defaults.$key"));
    }
}

if (! function_exists('lsetting')) {
    /** Localised setting: tries key_{locale}, then key_hy. */
    function lsetting(string $key)
    {
        return setting($key.'_'.app()->getLocale()) ?: setting($key.'_hy');
    }
}

if (! function_exists('tel_href')) {
    function tel_href(?string $phone): string
    {
        return 'tel:'.preg_replace('/[^0-9+]/', '', (string) $phone);
    }
}
