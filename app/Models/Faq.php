<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Faq extends Model
{
    use Translatable;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort')->orderBy('id');
    }

    /** Bump to re-apply database/data/faqs.php (replaces all FAQ entries, including admin edits). */
    public const DATA_VERSION = '2026-11-19b';

    /**
     * Replace the FAQ with the client's questions. Runs once per DATA_VERSION,
     * on hosts without a migrate step too (Vercel); afterwards the admin panel owns them.
     * The version row is claimed with one atomic UPDATE, so parallel first requests
     * (several serverless instances) can't import the list twice.
     */
    public static function syncClientFaqs(): void
    {
        $key = 'faq_data_version';
        try {
            if (Setting::get($key) === self::DATA_VERSION || ! Schema::hasTable('faqs')) {
                return;
            }
            DB::table('settings')->insertOrIgnore(['key' => $key, 'value' => null, 'created_at' => now(), 'updated_at' => now()]);
            $claimed = DB::table('settings')->where('key', $key)
                ->where(fn ($q) => $q->whereNull('value')->orWhere('value', '!=', self::DATA_VERSION))
                ->update(['value' => self::DATA_VERSION, 'updated_at' => now()]);
            if (! $claimed) {
                return; // another request is doing (or did) the import
            }
            try {
                DB::transaction(function () {
                    static::query()->delete();
                    foreach (require database_path('data/faqs.php') as $i => $f) {
                        static::create($f + ['sort' => $i, 'is_active' => true]);
                    }
                });
            } catch (\Throwable $e) {
                DB::table('settings')->where('key', $key)->update(['value' => null]);
                throw $e;
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
