<section class="w-full">
    <div class="flex items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">Tecnologías</flux:heading>
            <flux:subheading>Catálogo de tecnologías que puedes asociar a proyectos y experiencia laboral.</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create">Nueva tecnología</flux:button>
    </div>

    <flux:table class="mt-6">
        <flux:table.columns>
            <flux:table.column>Nombre</flux:table.column>
            <flux:table.column>Proyectos</flux:table.column>
            <flux:table.column>Experiencia</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->technologies as $technology)
                <flux:table.row :key="$technology->id">
                    <flux:table.cell variant="strong">
                        <flux:badge :color="$technology->color ?: 'zinc'" size="sm">{{ $technology->name }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $technology->projects_count }}</flux:table.cell>
                    <flux:table.cell>{{ $technology->experiences_count }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button size="sm" icon="pencil" wire:click="edit({{ $technology->id }})" />
                        <flux:button size="sm" icon="trash" variant="danger" wire:click="confirmDelete({{ $technology->id }})" />
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4">Aún no hay tecnologías registradas.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal name="technology-form" class="md:w-96">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Editar tecnología' : 'Nueva tecnología' }}</flux:heading>

            <flux:input wire:model="name" label="Nombre" required autofocus />
            <flux:input wire:model="icon" label="Ícono (Flux)" description="Nombre de un ícono de Flux, por ejemplo: code-bracket" />
            <flux:select wire:model="color" label="Color de la etiqueta">
                <flux:select.option value="">Predeterminado</flux:select.option>
                <flux:select.option value="red">Rojo</flux:select.option>
                <flux:select.option value="orange">Naranja</flux:select.option>
                <flux:select.option value="amber">Ámbar</flux:select.option>
                <flux:select.option value="lime">Lima</flux:select.option>
                <flux:select.option value="emerald">Esmeralda</flux:select.option>
                <flux:select.option value="cyan">Cian</flux:select.option>
                <flux:select.option value="blue">Azul</flux:select.option>
                <flux:select.option value="violet">Violeta</flux:select.option>
                <flux:select.option value="pink">Rosa</flux:select.option>
            </flux:select>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="technology-delete" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">¿Eliminar tecnología?</flux:heading>
            <flux:text>Se quitará de todos los proyectos y experiencias que la usan.</flux:text>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="delete">Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</section>
