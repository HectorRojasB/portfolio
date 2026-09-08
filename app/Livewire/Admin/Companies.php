<?php

namespace App\Livewire\Admin;

use App\Models\Company;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Compañías')]
class Companies extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public ?string $website_url = null;

    public ?string $location = null;

    public ?int $deletingId = null;

    public function create(): void
    {
        $this->resetForm();

        $this->modal('company-form')->show();
    }

    public function edit(int $id): void
    {
        $company = Company::findOrFail($id);

        $this->editingId = $company->id;
        $this->name = $company->name;
        $this->website_url = $company->website_url;
        $this->location = $company->location;

        $this->modal('company-form')->show();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('companies', 'name')->ignore($this->editingId)],
            'website_url' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = str($validated['name'])->slug();

        Company::updateOrCreate(['id' => $this->editingId], $validated);

        $this->modal('company-form')->close();
        $this->resetForm();

        Flux::toast(variant: 'success', text: 'Compañía guardada.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;

        $this->modal('company-delete')->show();
    }

    public function delete(): void
    {
        Company::whereKey($this->deletingId)->delete();

        $this->deletingId = null;
        $this->modal('company-delete')->close();

        Flux::toast(variant: 'success', text: 'Compañía eliminada.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'website_url', 'location']);
        $this->resetValidation();
    }

    /**
     * @return Collection<int, Company>
     */
    #[Computed]
    public function companies(): Collection
    {
        return Company::query()->withCount('experiences')->orderBy('name')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.companies');
    }
}
