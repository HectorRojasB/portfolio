<section class="w-full">
    <div class="flex items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">Intereses</flux:heading>
            <flux:subheading>Temas e intereses generales que se muestran en tu portafolio.</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create">Nuevo interés</flux:button>
    </div>

    <flux:table class="mt-6">
        <flux:table.columns>
            <flux:table.column>Nombre</flux:table.column>
            <flux:table.column>Descripción</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->interests as $interest)
                <flux:table.row :key="$interest->id">
                    <flux:table.cell variant="strong">
                        <div class="flex items-center gap-2">
                            <flux:icon :name="$interest->iconName()" class="size-4 text-zinc-400" />
                            {{ $interest->name_es }}
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>{{ $interest->description_es }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button size="sm" icon="pencil" wire:click="edit({{ $interest->id }})" />
                        <flux:button size="sm" icon="trash" variant="danger" wire:click="confirmDelete({{ $interest->id }})" />
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="3">Aún no hay intereses registrados.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal name="interest-form" class="md:w-[28rem]">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Editar interés' : 'Nuevo interés' }}</flux:heading>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="name_es" label="Nombre (Español)" required autofocus />
                <flux:input wire:model="name_en" label="Name (English)" />
            </div>

            <flux:input wire:model="icon" label="Ícono (Flux)" description="Nombre de un ícono de Flux, por ejemplo: sparkles" />

            <div class="grid grid-cols-2 gap-4">
                <flux:textarea wire:model="description_es" label="Descripción (Español)" rows="3" />
                <flux:textarea wire:model="description_en" label="Description (English)" rows="3" />
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="interest-delete" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">¿Eliminar interés?</flux:heading>
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
