<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
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
    public const DATA_VERSION = '2026-11-19';

    /**
     * Replace the FAQ with the client's questions. Runs once per DATA_VERSION,
     * on hosts without a migrate step too (Vercel); afterwards the admin panel owns them.
     */
    public static function syncClientFaqs(): void
    {
        try {
            if (Setting::get('faq_data_version') === self::DATA_VERSION || ! Schema::hasTable('faqs')) {
                return;
            }
            static::query()->delete();
            foreach (require database_path('data/faqs.php') as $i => $f) {
                static::create($f + ['sort' => $i, 'is_active' => true]);
            }
            Setting::put('faq_data_version', self::DATA_VERSION);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
