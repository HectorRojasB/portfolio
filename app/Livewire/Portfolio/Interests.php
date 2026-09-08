<?php

namespace App\Livewire\Portfolio;

use App\Models\Interest;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Interests extends Component
{
    public function render(): View
    {
        return view('livewire.portfolio.interests', [
            'interests' => Interest::query()->orderBy('name_es')->get(),
        ]);
    }
}
