<?php

namespace App\Livewire\Portfolio;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Technology;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Hero extends Component
{
    public function render(): View
    {
        return view('livewire.portfolio.hero', [
            'profile' => Profile::first(),
            'yearsOfExperience' => $this->yearsOfExperience(),
            'topTechnologies' => $this->topTechnologies(),
        ]);
    }

    private function yearsOfExperience(): ?int
    {
        $earliestStart = Experience::query()->min('start_date');

        if (! $earliestStart) {
            return null;
        }

        return (int) Carbon::parse($earliestStart)->diffInYears(now());
    }

    /**
     * @return Collection<int, Technology>
     */
    private function topTechnologies(): Collection
    {
        $latestExperience = Experience::query()
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->first();

        if (! $latestExperience) {
            return new Collection;
        }

        return $latestExperience->technologies()->orderBy('name')->limit(6)->get();
    }
}
