<?php

namespace App\Livewire\Portfolio;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Technology;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Projects extends Component
{
    public ?int $technology = null;

    public ?int $selectedProject = null;

    public int $galleryIndex = 0;

    public function filterBy(?int $technologyId): void
    {
        $this->technology = $this->technology === $technologyId ? null : $technologyId;
    }

    public function viewProject(int $projectId): void
    {
        $this->selectedProject = $projectId;
        $this->galleryIndex = 0;

        $this->modal('project-details')->show();
    }

    public function setGalleryImage(int $index): void
    {
        $this->galleryIndex = $index;
    }

    public function nextGalleryImage(): void
    {
        $total = $this->galleryImageCount();

        if ($total > 0) {
            $this->galleryIndex = ($this->galleryIndex + 1) % $total;
        }
    }

    public function previousGalleryImage(): void
    {
        $total = $this->galleryImageCount();

        if ($total > 0) {
            $this->galleryIndex = ($this->galleryIndex - 1 + $total) % $total;
        }
    }

    private function galleryImageCount(): int
    {
        if (! $this->selectedProject) {
            return 0;
        }

        return ProjectImage::query()->where('project_id', $this->selectedProject)->count();
    }

    /**
     * @return Collection<int, Technology>
     */
    #[Computed]
    public function technologies(): Collection
    {
        return Technology::query()
            ->whereHas('projects')
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection<int, Project>
     */
    #[Computed]
    public function projects(): Collection
    {
        return Project::query()
            ->with(['technologies', 'company'])
            ->when(
                $this->technology,
                fn ($query) => $query->whereRelation('technologies', 'technologies.id', $this->technology)
            )
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('started_at')
            ->get();
    }

    #[Computed]
    public function selectedProjectModel(): ?Project
    {
        if (! $this->selectedProject) {
            return null;
        }

        return Project::query()->with(['technologies', 'images', 'company'])->find($this->selectedProject);
    }

    public function render(): View
    {
        return view('livewire.portfolio.projects');
    }
}
