<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

        return is_file(public_path($rel)) ? asset($rel) : null;
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
