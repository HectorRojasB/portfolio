<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatedFields;
use Flux\Flux;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasTranslatedFields;

    protected $fillable = [
        'name_es',
        'name_en',
        'icon',
        'description_es',
        'description_en',
    ];

    private const DEFAULT_ICON = 'sparkles';

    public function iconName(): string
    {
        $icon = $this->icon ?: self::DEFAULT_ICON;

        return Flux::componentExists("icon.{$icon}") ? $icon : self::DEFAULT_ICON;
    }
}
