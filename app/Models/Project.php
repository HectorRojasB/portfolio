<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasTranslatedFields;

    protected $fillable = [
        'company_id',
        'context',
        'title',
        'slug',
        'excerpt_es',
        'excerpt_en',
        'description_es',
        'description_en',
        'thumbnail_path',
        'url',
        'repository_url',
        'status',
        'is_featured',
        'started_at',
        'finished_at',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'started_at' => 'date',
        'finished_at' => 'date',
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

    /**
     * @return HasMany<ProjectImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function statusLabel(): string
    {
        return __("portfolio.projects.status.{$this->status}");
    }

    public function contextLabel(): string
    {
        return $this->context === 'company' && $this->company
            ? $this->company->name
            : __("portfolio.projects.context.{$this->context}");
    }

    public function thumbnailUrl(): ?string
    {
        return $this->thumbnail_path ? Storage::disk('public')->url($this->thumbnail_path) : null;
    }

    public function period(): ?string
    {
        if (! $this->started_at) {
            return null;
        }

        Carbon::setLocale(app()->getLocale());

        $start = Carbon::parse($this->started_at)->translatedFormat('M Y');

        if (! $this->finished_at) {
            return $start;
        }

        $end = Carbon::parse($this->finished_at)->translatedFormat('M Y');

        return "{$start} - {$end}";
    }
}
