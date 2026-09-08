<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Technology;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Proyectos')]
class Projects extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $title = '';

    public ?string $excerpt_es = null;

    public ?string $excerpt_en = null;

    public ?string $description_es = null;

    public ?string $description_en = null;

    public ?UploadedFile $thumbnail = null;

    public ?string $existingThumbnailUrl = null;

    /** @var array<int, UploadedFile> */
    public array $newImages = [];

    public ?string $url = null;

    public ?string $repository_url = null;

    public string $status = 'completed';

    public bool $is_featured = false;

    public ?string $started_at = null;

    public ?string $finished_at = null;

    public int $sort_order = 0;

    /** @var array<int> */
    public array $selectedTechnologies = [];

    public ?int $deletingId = null;

    public function create(): void
    {
        $this->resetForm();

        $this->modal('project-form')->show();
    }

    public function edit(int $id): void
    {
        $project = Project::query()->with('technologies')->findOrFail($id);

        $this->editingId = $project->id;
        $this->title = $project->title;
        $this->excerpt_es = $project->excerpt_es;
        $this->excerpt_en = $project->excerpt_en;
        $this->description_es = $project->description_es;
        $this->description_en = $project->description_en;
        $this->existingThumbnailUrl = $project->thumbnailUrl();
        $this->url = $project->url;
        $this->repository_url = $project->repository_url;
        $this->status = $project->status;
        $this->is_featured = $project->is_featured;
        $this->started_at = $project->started_at?->format('Y-m-d');
        $this->finished_at = $project->finished_at?->format('Y-m-d');
        $this->sort_order = $project->sort_order;
        $this->selectedTechnologies = $project->technologies->pluck('id')->all();

        $this->modal('project-form')->show();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'excerpt_es' => 'nullable|string|max:255',
            'excerpt_en' => 'nullable|string|max:255',
            'description_es' => 'nullable|string',
            'description_en' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'newImages' => 'array',
            'newImages.*' => 'image|max:2048',
            'url' => 'nullable|url|max:255',
            'repository_url' => 'nullable|url|max:255',
            'status' => 'required|in:in_progress,completed,archived',
            'is_featured' => 'boolean',
            'started_at' => 'nullable|date',
            'finished_at' => 'nullable|date|after_or_equal:started_at',
            'sort_order' => 'integer|min:0',
            'selectedTechnologies' => 'array',
            'selectedTechnologies.*' => 'exists:technologies,id',
        ]);

        if (! $this->editingId) {
            $validated['slug'] = $this->uniqueSlug($validated['title']);
        }

        $technologyIds = $validated['selectedTechnologies'];
        unset($validated['selectedTechnologies']);

        $newImages = $validated['newImages'];
        unset($validated['newImages']);

        if ($this->thumbnail) {
            $validated['thumbnail_path'] = $this->thumbnail->store('projects', 'public');
        }
        unset($validated['thumbnail']);

        $project = Project::updateOrCreate(['id' => $this->editingId], $validated);
        $project->technologies()->sync($technologyIds);

        $nextOrder = $project->images()->count();
        foreach ($newImages as $image) {
            $project->images()->create([
                'path' => $image->store('projects/gallery', 'public'),
                'sort_order' => $nextOrder++,
            ]);
        }

        $this->modal('project-form')->close();
        $this->resetForm();

        Flux::toast(variant: 'success', text: 'Proyecto guardado.');
    }

    public function removeImage(int $imageId): void
    {
        $image = ProjectImage::whereKey($imageId)->first();

        if (! $image) {
            return;
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        Flux::toast(variant: 'success', text: 'Imagen eliminada.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;

        $this->modal('project-delete')->show();
    }

    public function delete(): void
    {
        Project::whereKey($this->deletingId)->delete();

        $this->deletingId = null;
        $this->modal('project-delete')->close();

        Flux::toast(variant: 'success', text: 'Proyecto eliminado.');
    }

    private function uniqueSlug(string $title): string
    {
        $base = str($title)->slug();
        $slug = $base;
        $suffix = 1;

        while (Project::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-".++$suffix;
        }

        return (string) $slug;
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'title', 'excerpt_es', 'excerpt_en', 'description_es', 'description_en',
            'thumbnail', 'existingThumbnailUrl', 'newImages',
            'url', 'repository_url', 'is_featured', 'started_at', 'finished_at', 'selectedTechnologies',
        ]);
        $this->status = 'completed';
        $this->sort_order = 0;
        $this->resetValidation();
    }

    /**
     * @return Collection<int, Project>
     */
    #[Computed]
    public function projects(): Collection
    {
        return Project::query()
            ->with('technologies')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('started_at')
            ->get();
    }

    /**
     * @return Collection<int, Technology>
     */
    #[Computed]
    public function technologies(): Collection
    {
        return Technology::query()->orderBy('name')->get();
    }

    /**
     * @return Collection<int, ProjectImage>
     */
    #[Computed]
    public function galleryImages(): Collection
    {
        if (! $this->editingId) {
            return new Collection;
        }

        return ProjectImage::query()
            ->where('project_id', $this->editingId)
            ->orderBy('sort_order')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.admin.projects');
    }
}
