<?php

namespace App\Livewire\Admin;

use App\Models\Technology;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tecnologías')]
class Technologies extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public ?string $icon = null;

    public ?string $color = null;

    public ?int $deletingId = null;

    public function create(): void
    {
        $this->resetForm();

        $this->modal('technology-form')->show();
    }

    public function edit(int $id): void
    {
        $technology = Technology::findOrFail($id);

        $this->editingId = $technology->id;
        $this->name = $technology->name;
        $this->icon = $technology->icon;
        $this->color = $technology->color;

        $this->modal('technology-form')->show();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('technologies', 'name')->ignore($this->editingId)],
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = str($validated['name'])->slug();

        Technology::updateOrCreate(['id' => $this->editingId], $validated);

        $this->modal('technology-form')->close();
        $this->resetForm();

        Flux::toast(variant: 'success', text: 'Tecnología guardada.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;

        $this->modal('technology-delete')->show();
    }

    public function delete(): void
    {
        Technology::whereKey($this->deletingId)->delete();

        $this->deletingId = null;
        $this->modal('technology-delete')->close();

        Flux::toast(variant: 'success', text: 'Tecnología eliminada.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'icon', 'color']);
        $this->resetValidation();
    }

    /**
     * @return Collection<int, Technology>
     */
    #[Computed]
    public function technologies(): Collection
    {
        return Technology::query()->withCount(['projects', 'experiences'])->orderBy('name')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.technologies');
    }
}
