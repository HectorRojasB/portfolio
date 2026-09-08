<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasTranslatedFields;

    protected $fillable = [
        'full_name',
        'headline_es',
        'headline_en',
        'bio_es',
        'bio_en',
        'email',
        'phone',
        'location',
        'avatar_path',
        'resume_path',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'website_url',
        'available_for_hire',
    ];

    protected $casts = [
        'available_for_hire' => 'boolean',
    ];

    public function avatarUrl(): ?string
    {
        return $this->avatar_path ? Storage::disk('public')->url($this->avatar_path) : null;
    }

    public function resumeUrl(): ?string
    {
        return $this->resume_path ? Storage::disk('public')->url($this->resume_path) : null;
    }
}
