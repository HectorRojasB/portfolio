<?php

namespace App\Livewire\Admin;

use App\Models\Profile as ProfileModel;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Perfil del portafolio')]
class Profile extends Component
{
    use WithFileUploads;

    public ?int $profileId = null;

    public string $full_name = '';

    public ?string $headline_es = null;

    public ?string $headline_en = null;

    public ?string $bio_es = null;

    public ?string $bio_en = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $location = null;

    public ?UploadedFile $avatar = null;

    public ?string $existingAvatarUrl = null;

    public ?UploadedFile $resume = null;

    public ?string $existingResumeUrl = null;

    public ?string $github_url = null;

    public ?string $linkedin_url = null;

    public ?string $twitter_url = null;

    public ?string $website_url = null;

    public bool $available_for_hire = true;

    public function mount(): void
    {
        $profile = ProfileModel::first();

        if (! $profile) {
            return;
        }

        $this->profileId = $profile->id;
        $this->full_name = $profile->full_name;
        $this->headline_es = $profile->headline_es;
        $this->headline_en = $profile->headline_en;
        $this->bio_es = $profile->bio_es;
        $this->bio_en = $profile->bio_en;
        $this->email = $profile->email;
        $this->phone = $profile->phone;
        $this->location = $profile->location;
        $this->existingAvatarUrl = $profile->avatarUrl();
        $this->existingResumeUrl = $profile->resumeUrl();
        $this->github_url = $profile->github_url;
        $this->linkedin_url = $profile->linkedin_url;
        $this->twitter_url = $profile->twitter_url;
        $this->website_url = $profile->website_url;
        $this->available_for_hire = $profile->available_for_hire;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'full_name' => 'required|string|max:255',
            'headline_es' => 'nullable|string|max:255',
            'headline_en' => 'nullable|string|max:255',
            'bio_es' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'resume' => 'nullable|mimes:pdf|max:5120',
            'github_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'website_url' => 'nullable|url|max:255',
            'available_for_hire' => 'boolean',
        ]);

        if ($this->avatar) {
            $validated['avatar_path'] = $this->avatar->store('avatars', 'public');
        }
        unset($validated['avatar']);

        if ($this->resume) {
            $validated['resume_path'] = $this->resume->store('resumes', 'public');
        }
        unset($validated['resume']);

        $profile = ProfileModel::updateOrCreate(['id' => $this->profileId], $validated);

        $this->profileId = $profile->id;
        $this->existingAvatarUrl = $profile->avatarUrl();
        $this->existingResumeUrl = $profile->resumeUrl();
        $this->reset(['avatar', 'resume']);

        Flux::toast(variant: 'success', text: 'Perfil actualizado.');
    }

    public function render(): View
    {
        return view('livewire.admin.profile');
    }
}
