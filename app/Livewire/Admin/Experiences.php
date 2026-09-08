<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use App\Models\Experience;
use App\Models\Technology;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Experiencia laboral')]
class Experiences extends Component
{
    public ?int $editingId = null;

    public ?int $company_id = null;

    public string $position_es = '';

    public ?string $position_en = null;

    public string $employment_type = 'full_time';

    public ?string $description_es = null;

    public ?string $description_en = null;

    public ?string $location = null;

    public bool $is_remote = false;

    public ?string $start_date = null;

    public ?string $end_date = null;

    public bool $is_current = false;

    /** @var array<int> */
    public array $selectedTechnologies = [];

    public ?int $deletingId = null;

    public function create(): void
    {
        $this->resetForm();

        $this->modal('experience-form')->show();
    }

    public function edit(int $id): void
    {
        $experience = Experience::query()->with('technologies')->findOrFail($id);

        $this->editingId = $experience->id;
        $this->company_id = $experience->company_id;
        $this->position_es = $experience->position_es;
        $this->position_en = $experience->position_en;
        $this->employment_type = $experience->employment_type;
        $this->description_es = $experience->description_es;
        $this->description_en = $experience->description_en;
        $this->location = $experience->location;
        $this->is_remote = $experience->is_remote;
        $this->start_date = $experience->start_date->format('Y-m-d');
        $this->end_date = $experience->end_date?->format('Y-m-d');
        $this->is_current = $experience->is_current;
        $this->selectedTechnologies = $experience->technologies->pluck('id')->all();

        $this->modal('experience-form')->show();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'company_id' => 'required|exists:companies,id',
            'position_es' => 'required|string|max:255',
            'position_en' => 'nullable|string|max:255',
            'employment_type' => 'required|in:full_time,part_time,freelance,internship,contract',
            'description_es' => 'nullable|string',
            'description_en' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_remote' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'selectedTechnologies' => 'array',
            'selectedTechnologies.*' => 'exists:technologies,id',
        ]);

        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }

        $technologyIds = $validated['selectedTechnologies'];
        unset($validated['selectedTechnologies']);

        $experience = Experience::updateOrCreate(['id' => $this->editingId], $validated);
        $experience->technologies()->sync($technologyIds);

        $this->modal('experience-form')->close();
        $this->resetForm();

        Flux::toast(variant: 'success', text: 'Experiencia guardada.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;

        $this->modal('experience-delete')->show();
    }

    public function delete(): void
    {
        Experience::whereKey($this->deletingId)->delete();

        $this->deletingId = null;
        $this->modal('experience-delete')->close();

        Flux::toast(variant: 'success', text: 'Experiencia eliminada.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'company_id', 'position_es', 'position_en', 'description_es', 'description_en', 'location',
            'is_remote', 'start_date', 'end_date', 'is_current', 'selectedTechnologies',
        ]);
        $this->employment_type = 'full_time';
        $this->resetValidation();
    }

    /**
     * @return Collection<int, Experience>
     */
    #[Computed]
    public function experiences(): Collection
    {
        return Experience::query()
            ->with(['company', 'technologies'])
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();
    }

    /**
     * @return Collection<int, Company>
     */
    #[Computed]
    public function companies(): Collection
    {
        return Company::query()->orderBy('name')->get();
    }

    /**
     * @return Collection<int, Technology>
     */
    #[Computed]
    public function technologies(): Collection
    {
        return Technology::query()->orderBy('name')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.experiences');
    }
}
