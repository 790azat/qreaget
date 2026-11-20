<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Department extends Model
{
    use Translatable;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort')->orderBy('id');
    }

    /** Photo for the department, if one is shipped in public/images/departments/{slug}.jpg */
    public function getImageUrlAttribute(): ?string
    {
        $rel = 'images/departments/'.$this->slug.'.jpg';

        $path = public_path($rel);

        // ?v= busts browser/CDN caches when a photo is replaced under the same name
        return is_file($path) ? asset($rel).'?v='.substr(md5_file($path), 0, 8) : null;
    }

    /** Bump when database/data/department_texts_hy.php changes and should be re-applied. */
    public const TEXTS_VERSION = '1';

    /** Long page text for the current locale (no fallback: ru/en pages stay short until translated). */
    public function contentHtml(): ?string
    {
        $html = $this->getAttribute('content_'.app()->getLocale());
        if (blank($html)) {
            return null;
        }
        // Admin-edited HTML: keep simple formatting tags only, drop all attributes.
        $html = strip_tags($html, '<h2><h3><h4><p><ul><ol><li><strong><b><em><i><br>');

        return preg_replace('/<(\w+)\s[^>]*>/', '<$1>', $html);
    }

    /** Add the content_* columns when missing (hosts without a migrate step, e.g. Vercel). */
    public static function ensureContentColumns(): void
    {
        foreach (['hy', 'ru', 'en'] as $l) {
            if (! Schema::hasColumn('departments', "content_$l")) {
                Schema::table('departments', fn (Blueprint $t) => $t->text("content_$l")->nullable());
            }
        }
    }

    /**
     * One-time import of the Armenian service texts from the client's previous site.
     * Runs once per TEXTS_VERSION; afterwards the admin panel owns the texts.
     */
    public static function syncTexts(): void
    {
        try {
            if (Setting::get('department_texts_version') === self::TEXTS_VERSION || ! Schema::hasTable('departments')) {
                return;
            }
            static::ensureContentColumns();
            if (static::query()->doesntExist()) {
                return; // fresh install: the seeder calls this again after creating departments
            }
            foreach (require database_path('data/department_texts_hy.php') as $slug => $t) {
                $data = ['description_hy' => $t['description']];
                if (! empty($t['content'])) {
                    $data['content_hy'] = trim($t['content']);
                }
                static::query()->where('slug', $slug)->update($data);
            }
            Setting::put('department_texts_version', self::TEXTS_VERSION);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
