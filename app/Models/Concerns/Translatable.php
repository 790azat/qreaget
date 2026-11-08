<?php

namespace App\Models\Concerns;

trait Translatable
{
    public function t(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $value = $this->getAttribute("{$field}_{$locale}");

        return filled($value) ? $value : $this->getAttribute("{$field}_hy");
    }
}
