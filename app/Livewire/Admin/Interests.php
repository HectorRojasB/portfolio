<?php

namespace App\Livewire\Admin;

use App\Models\Interest;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Intereses')]
class Interests extends Component
{
    public ?int $editingId = null;

    public string $name_es = '';

    public ?string $name_en = null;

    public ?string $icon = null;

    public ?string $description_es = null;

    public ?string $description_en = null;

    public ?int $deletingId = null;

    public function create(): void
    {
        $this->resetForm();

        $this->modal('interest-form')->show();
    }

    public function edit(int $id): void
    {
        $interest = Interest::findOrFail($id);

        $this->editingId = $interest->id;
        $this->name_es = $interest->name_es;
        $this->name_en = $interest->name_en;
        $this->icon = $interest->icon;
        $this->description_es = $interest->description_es;
        $this->description_en = $interest->description_en;

        $this->modal('interest-form')->show();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name_es' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'icon' => ['nullable', 'string', 'max:255', function (string $attribute, mixed $value, \Closure $fail): void {
                if ($value && ! Flux::componentExists("icon.{$value}")) {
                    $fail('Ese ícono no existe en Flux. Revisa el nombre en fluxui.dev/icons.');
                }
            }],
            'description_es' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        Interest::updateOrCreate(['id' => $this->editingId], $validated);

        $this->modal('interest-form')->close();
        $this->resetForm();

        Flux::toast(variant: 'success', text: 'Interés guardado.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;

        $this->modal('interest-delete')->show();
    }

    public function delete(): void
    {
        Interest::whereKey($this->deletingId)->delete();

        $this->deletingId = null;
        $this->modal('interest-delete')->close();

        Flux::toast(variant: 'success', text: 'Interés eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name_es', 'name_en', 'icon', 'description_es', 'description_en']);
        $this->resetValidation();
    }

    /**
     * @return Collection<int, Interest>
     */
    #[Computed]
    public function interests(): Collection
    {
        return Interest::query()->orderBy('name_es')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.interests');
    }
}
