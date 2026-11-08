<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected static ?array $cache = null;

    public static function all_cached(): array
    {
        if (static::$cache === null) {
            try {
                static::$cache = static::query()->pluck('value', 'key')->all();
            } catch (\Throwable) {
                static::$cache = [];
            }
        }

        return static::$cache;
    }

    public static function get(string $key, $default = null)
    {
        $all = static::all_cached();
        $v = $all[$key] ?? null;

        return filled($v) ? $v : $default;
    }

    public static function put(string $key, $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        static::$cache = null;
    }
}
