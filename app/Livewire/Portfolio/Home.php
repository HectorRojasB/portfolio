<?php

namespace App\Livewire\Portfolio;

use App\Models\Profile;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::portfolio')]
class Home extends Component
{
    public function render(): View
    {
        return view('livewire.portfolio.home')->title($this->pageTitle());
    }

    private function pageTitle(): string
    {
        $profile = Profile::first();

        if (! $profile) {
            return config('app.name', 'Portfolio');
        }

        $headline = $profile->trans('headline');

        return $headline ? "{$profile->full_name} · {$headline}" : $profile->full_name;
    }
}
