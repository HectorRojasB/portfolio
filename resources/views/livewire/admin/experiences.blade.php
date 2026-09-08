<section class="w-full">
    <div class="flex items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">Experiencia laboral</flux:heading>
            <flux:subheading>Los puestos de trabajo que se muestran en tu portafolio.</flux:subheading>
        </div>

        @if ($this->companies->isEmpty())
            <flux:button variant="primary" icon="plus" disabled>Nueva experiencia</flux:button>
        @else
            <flux:button variant="primary" icon="plus" wire:click="create">Nueva experiencia</flux:button>
        @endif
    </div>

    @if ($this->companies->isEmpty())
        <flux:callout variant="warning" icon="exclamation-triangle" class="mt-6">
            <flux:callout.text>Primero debes crear al menos una <a href="{{ route('admin.companies') }}" class="underline" wire:navigate>compañía</a>.</flux:callout.text>
        </flux:callout>
    @endif

    <flux:table class="mt-6">
        <flux:table.columns>
            <flux:table.column>Puesto</flux:table.column>
            <flux:table.column>Compañía</flux:table.column>
            <flux:table.column>Periodo</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->experiences as $experience)
                <flux:table.row :key="$experience->id">
                    <flux:table.cell variant="strong">
                        {{ $experience->position_es }}
                        @if ($experience->is_current)
                            <flux:badge color="lime" size="sm">Actual</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>{{ $experience->company->name }}</flux:table.cell>
                    <flux:table.cell>{{ $experience->period() }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button size="sm" icon="pencil" wire:click="edit({{ $experience->id }})" />
                        <flux:button size="sm" icon="trash" variant="danger" wire:click="confirmDelete({{ $experience->id }})" />
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4">Aún no hay experiencia laboral registrada.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal name="experience-form" class="md:w-[32rem]">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Editar experiencia' : 'Nueva experiencia' }}</flux:heading>

            <flux:select wire:model="company_id" label="Compañía" placeholder="Selecciona una compañía" required>
                @foreach ($this->companies as $company)
                    <flux:select.option value="{{ $company->id }}">{{ $company->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="position_es" label="Puesto (Español)" required />
                <flux:input wire:model="position_en" label="Position (English)" />
            </div>

            <flux:select wire:model="employment_type" label="Tipo de empleo" required>
                <flux:select.option value="full_time">Tiempo completo</flux:select.option>
                <flux:select.option value="part_time">Medio tiempo</flux:select.option>
                <flux:select.option value="freelance">Freelance</flux:select.option>
                <flux:select.option value="internship">Prácticas</flux:select.option>
                <flux:select.option value="contract">Contrato</flux:select.option>
            </flux:select>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="start_date" label="Fecha de inicio" type="date" required />
                <flux:input wire:model="end_date" label="Fecha de fin" type="date" :disabled="$is_current" />
            </div>

            <div class="flex gap-6">
                <flux:switch wire:model="is_current" label="Puesto actual" />
                <flux:switch wire:model="is_remote" label="Remoto" />
            </div>

            <flux:input wire:model="location" label="Ubicación" />

            <div class="grid grid-cols-2 gap-4">
                <flux:textarea wire:model="description_es" label="Descripción (Español)" rows="3" />
                <flux:textarea wire:model="description_en" label="Description (English)" rows="3" />
            </div>

            @if ($this->technologies->isNotEmpty())
                <flux:checkbox.group wire:model="selectedTechnologies" label="Tecnologías">
                    @foreach ($this->technologies as $technology)
                        <flux:checkbox value="{{ $technology->id }}" label="{{ $technology->name }}" />
                    @endforeach
                </flux:checkbox.group>
            @endif

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="experience-delete" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">¿Eliminar experiencia?</flux:heading>
            <flux:text>Esta acción no se puede deshacer.</flux:text>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="delete">Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</section>
