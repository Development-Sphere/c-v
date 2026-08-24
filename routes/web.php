<?php

use App\Http\Controllers\CvController;
use App\Livewire\CvBuilder;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::post('/cv', [CvController::class, 'store'])->name('cv.store');

Route::get('/cv/{cv}/edit', CvBuilder::class)
    ->middleware('can:update,cv')
    ->name('cv.edit');

Route::get('/cv/{cv}/preview', [CvController::class, 'preview'])
    ->middleware('can:view,cv')
    ->name('cv.preview');

Route::get('/cv/{cv}/export', [CvController::class, 'export'])
    ->middleware(['auth', 'can:view,cv'])
    ->name('cv.export');

Route::get('dashboard', fn () => view('dashboard', [
    'cvs' => auth()->user()->cvs()->latest()->get(),
]))->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
