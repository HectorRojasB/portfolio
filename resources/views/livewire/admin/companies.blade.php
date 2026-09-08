<section class="w-full">
    <div class="flex items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">Compañías</flux:heading>
            <flux:subheading>Empresas donde has trabajado o colaborado.</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create">Nueva compañía</flux:button>
    </div>

    <flux:table class="mt-6">
        <flux:table.columns>
            <flux:table.column>Nombre</flux:table.column>
            <flux:table.column>Ubicación</flux:table.column>
            <flux:table.column>Experiencias</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->companies as $company)
                <flux:table.row :key="$company->id">
                    <flux:table.cell variant="strong">
                        @if ($company->website_url)
                            <a href="{{ $company->website_url }}" target="_blank" class="hover:underline">{{ $company->name }}</a>
                        @else
                            {{ $company->name }}
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>{{ $company->location }}</flux:table.cell>
                    <flux:table.cell>{{ $company->experiences_count }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button size="sm" icon="pencil" wire:click="edit({{ $company->id }})" />
                        <flux:button size="sm" icon="trash" variant="danger" wire:click="confirmDelete({{ $company->id }})" />
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4">Aún no hay compañías registradas.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal name="company-form" class="md:w-96">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Editar compañía' : 'Nueva compañía' }}</flux:heading>

            <flux:input wire:model="name" label="Nombre" required autofocus />
            <flux:input wire:model="website_url" label="Sitio web" type="url" placeholder="https://" />
            <flux:input wire:model="location" label="Ubicación" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="company-delete" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">¿Eliminar compañía?</flux:heading>
            <flux:text>También se eliminará la experiencia laboral asociada a esta compañía.</flux:text>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="delete">Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</section>
