<section class="w-full">
    <div class="flex items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">Proyectos</flux:heading>
            <flux:subheading>Los proyectos que se muestran en tu portafolio.</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create">Nuevo proyecto</flux:button>
    </div>

    <flux:table class="mt-6">
        <flux:table.columns>
            <flux:table.column>Proyecto</flux:table.column>
            <flux:table.column>Origen</flux:table.column>
            <flux:table.column>Estado</flux:table.column>
            <flux:table.column>Destacado</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->projects as $project)
                <flux:table.row :key="$project->id">
                    <flux:table.cell variant="strong">{{ $project->title }}</flux:table.cell>
                    <flux:table.cell>{{ $project->contextLabel() }}</flux:table.cell>
                    <flux:table.cell>{{ $project->statusLabel() }}</flux:table.cell>
                    <flux:table.cell>
                        @if ($project->is_featured)
                            <flux:icon name="star" variant="solid" class="size-4 text-amber-400" />
                        @endif
                    </flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button size="sm" icon="pencil" wire:click="edit({{ $project->id }})" />
                        <flux:button size="sm" icon="trash" variant="danger" wire:click="confirmDelete({{ $project->id }})" />
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5">Aún no hay proyectos registrados.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal name="project-form" class="md:w-[32rem]">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? 'Editar proyecto' : 'Nuevo proyecto' }}</flux:heading>

            <flux:input wire:model="title" label="Título" required autofocus />

            <div class="grid grid-cols-2 gap-4">
                <flux:select wire:model.live="context" label="Tipo de proyecto" required>
                    <flux:select.option value="personal">Proyecto personal</flux:select.option>
                    <flux:select.option value="freelance">Freelance</flux:select.option>
                    <flux:select.option value="company">Compañía</flux:select.option>
                </flux:select>

                @if ($context === 'company')
                    <flux:select wire:model="company_id" label="Compañía" placeholder="Selecciona una compañía" required>
                        @foreach ($this->companies as $company)
                            <flux:select.option value="{{ $company->id }}">{{ $company->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="excerpt_es" label="Resumen breve (Español)" description="Se muestra en la tarjeta del proyecto" />
                <flux:input wire:model="excerpt_en" label="Short summary (English)" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:textarea wire:model="description_es" label="Descripción (Español)" rows="3" />
                <flux:textarea wire:model="description_en" label="Description (English)" rows="3" />
            </div>

            <div>
                @if ($thumbnail)
                    <img src="{{ $thumbnail->temporaryUrl() }}" class="mb-2 h-24 rounded-lg object-cover">
                @elseif ($existingThumbnailUrl)
                    <img src="{{ $existingThumbnailUrl }}" class="mb-2 h-24 rounded-lg object-cover">
                @endif
                <flux:input type="file" wire:model="thumbnail" label="Miniatura" accept="image/*" />
            </div>

            <div>
                <flux:label>Galería de fotos (opcional)</flux:label>

                @if ($this->galleryImages->isNotEmpty())
                    <div class="mt-2 grid grid-cols-4 gap-2">
                        @foreach ($this->galleryImages as $image)
                            <div class="group relative" wire:key="gallery-{{ $image->id }}">
                                <img src="{{ $image->url() }}" class="aspect-square w-full rounded-lg object-cover">
                                <button
                                    type="button"
                                    wire:click="removeImage({{ $image->id }})"
                                    wire:confirm="¿Eliminar esta imagen?"
                                    class="absolute top-1 right-1 flex size-6 items-center justify-center rounded-full bg-black/60 text-white opacity-0 transition group-hover:opacity-100"
                                >
                                    <flux:icon name="x-mark" class="size-4" />
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <flux:input type="file" wire:model="newImages" multiple label="Agregar fotos" accept="image/*" class="mt-2" />

                @if ($newImages)
                    <div class="mt-2 grid grid-cols-4 gap-2">
                        @foreach ($newImages as $newImage)
                            <img src="{{ $newImage->temporaryUrl() }}" class="aspect-square w-full rounded-lg object-cover">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="url" label="URL de demo" type="url" placeholder="https://" />
                <flux:input wire:model="repository_url" label="Repositorio" type="url" placeholder="https://" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:select wire:model="status" label="Estado" required>
                    <flux:select.option value="in_progress">En progreso</flux:select.option>
                    <flux:select.option value="completed">Completado</flux:select.option>
                    <flux:select.option value="archived">Archivado</flux:select.option>
                </flux:select>
                <flux:input wire:model="sort_order" label="Orden" type="number" min="0" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="started_at" label="Fecha de inicio" type="date" />
                <flux:input wire:model="finished_at" label="Fecha de fin" type="date" />
            </div>

            <flux:switch wire:model="is_featured" label="Destacar este proyecto" />

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

    <flux:modal name="project-delete" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">¿Eliminar proyecto?</flux:heading>
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
