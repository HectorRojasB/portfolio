<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Experience extends Model
{
    use HasTranslatedFields;

    protected $fillable = [
        'company_id',
        'position_es',
        'position_en',
        'employment_type',
        'description_es',
        'description_en',
        'location',
        'is_remote',
        'start_date',
        'end_date',
        'is_current',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_remote' => 'boolean',
        'is_current' => 'boolean',
    ];

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsToMany<Technology, $this>
     */
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function employmentTypeLabel(): string
    {
        return __("portfolio.experience.employment_type.{$this->employment_type}");
    }

    public function period(): string
    {
        $locale = app()->getLocale();
        Carbon::setLocale($locale);

        $start = Carbon::parse($this->start_date)->translatedFormat('M Y');
        $end = $this->is_current || ! $this->end_date
            ? __('portfolio.experience.present')
            : Carbon::parse($this->end_date)->translatedFormat('M Y');

        return "{$start} - {$end}";
    }
}
