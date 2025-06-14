<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterProjectController;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('master-project', [MasterProjectController::class, 'index'])->name('master-projects.index');
    Route::get('master-project/create', [MasterProjectController::class, 'create'])->name('master-projects.create');
    Route::post('master-project/store', [MasterProjectController::class, 'store'])->name('master-projects.store');
    Route::get('master-project/{id}/show', [MasterProjectController::class, 'show'])->name('master-projects.show');

    Route::get('account',[AccountController::class, 'index'])->name('account.index');
    Route::get('account/create',[AccountController::class, 'create'])->name('account.create');
    Route::post('account/store',[AccountController::class, 'store'])->name('account.store');
    Route::get('account/{id}/show', [AccountController::class, 'show'])->name('account.show');
    Route::get('account/{id}/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('account/{id}/update', [AccountController::class, 'update'])->name('account.update');
    Route::delete('account/{id}/destroy', [AccountController::class, 'destroy'])->name('account.destroy');

});

require __DIR__.'/auth.php';
