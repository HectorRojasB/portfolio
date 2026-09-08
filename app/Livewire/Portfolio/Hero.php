<?php

namespace App\Livewire\Portfolio;

use App\Models\Profile;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Hero extends Component
{
    public function render(): View
    {
        return view('livewire.portfolio.hero', [
            'profile' => Profile::first(),
        ]);
    }
}
