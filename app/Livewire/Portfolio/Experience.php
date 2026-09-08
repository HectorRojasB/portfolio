<?php

namespace App\Livewire\Portfolio;

use App\Models\Experience as ExperienceModel;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Experience extends Component
{
    public function render(): View
    {
        return view('livewire.portfolio.experience', [
            'experiences' => ExperienceModel::query()
                ->with(['company', 'technologies'])
                ->orderByDesc('is_current')
                ->orderByDesc('start_date')
                ->get(),
        ]);
    }
}
