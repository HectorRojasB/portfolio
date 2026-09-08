<?php

use App\Livewire\Portfolio\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $locale = request()->getPreferredLanguage(config('portfolio.locales'))
        ?? config('portfolio.default_locale');

    return redirect("/{$locale}");
});

Route::livewire('/{locale}', Home::class)
    ->whereIn('locale', config('portfolio.locales'))
    ->middleware('setlocale')
    ->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
