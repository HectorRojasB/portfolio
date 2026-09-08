<?php

use App\Livewire\Admin\Companies;
use App\Livewire\Admin\Experiences;
use App\Livewire\Admin\Interests;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\Projects;
use App\Livewire\Admin\Technologies;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/perfil');

    Route::livewire('perfil', Profile::class)->name('profile.edit');
    Route::livewire('companias', Companies::class)->name('companies');
    Route::livewire('experiencia', Experiences::class)->name('experiences');
    Route::livewire('proyectos', Projects::class)->name('projects');
    Route::livewire('tecnologias', Technologies::class)->name('technologies');
    Route::livewire('intereses', Interests::class)->name('interests');
});
