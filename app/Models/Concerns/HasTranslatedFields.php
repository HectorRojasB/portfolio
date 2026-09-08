<?php

namespace App\Models\Concerns;

trait HasTranslatedFields
{
    /**
     * Resolve a translatable field for the current app locale, falling back
     * to Spanish and then English when the current locale's value is empty.
     */
    public function trans(string $field): ?string
    {
        $locale = app()->getLocale();

        return $this->{"{$field}_{$locale}"}
            ?: $this->{"{$field}_es"}
            ?: $this->{"{$field}_en"};
    }
}
