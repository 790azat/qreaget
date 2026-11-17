<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Translatable;

    protected $guarded = ['id'];

    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    /** Photos without text shipped in public/images/articles, used when no cover is set. */
    public const DEFAULT_COVERS = ['documents', 'research', 'laboratory', 'road'];

    private const SLUG_COVERS = [
        'before-contacting-an-expert' => 'documents',
        'how-to-choose-expertise' => 'laboratory',
        'how-the-process-works' => 'research',
    ];

    /** Cover image: the uploaded/linked one, otherwise a stock photo picked per article. */
    public function getCoverUrlAttribute(): string
    {
        if ($this->image_url) {
            return str_starts_with($this->image_url, '/') ? url($this->image_url) : $this->image_url;
        }
        $name = self::SLUG_COVERS[$this->slug] ?? self::DEFAULT_COVERS[crc32((string) ($this->slug ?: $this->id)) % count(self::DEFAULT_COVERS)];

        return asset('images/articles/'.$name.'.jpg');
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->orderByDesc('published_at')->orderByDesc('id');
    }
}
