<?php

use App\Http\Controllers\SubAccountController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterProjectController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaController;
use App\Http\Controllers\GeneralleadgerController;
use App\Http\Controllers\IncomeStatementController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Vendor;
use App\Http\Controllers\VendorController;
use App\Models\Generalleadger;
use App\Models\Item;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'dashboard'])
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
Route::get('master-project/{id}/edit', [MasterProjectController::class, 'edit'])->name('master-projects.edit');
Route::put('master-project/{id}/update', [MasterProjectController::class, 'update'])->name('master-projects.update');
Route::post('project-progress/{project}', [MasterProjectController::class, 'storeProgress'])
     ->name('project-progress.store');   
Route::get('account',[AccountController::class, 'index'])->name('account.index');
    Route::get('account/create',[AccountController::class, 'create'])->name('account.create');
    Route::post('account/store',[AccountController::class, 'store'])->name('account.store');
    Route::get('account/{id}/show', [AccountController::class, 'show'])->name('account.show');
    Route::get('account/{id}/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('account/{id}/update', [AccountController::class, 'update'])->name('account.update');
    Route::delete('account/{id}/destroy', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::get('account/{id}/sub-account/create', [SubAccountController::class, 'create'])->name('account.sub-account.create');
    Route::post('account/{id}/sub-account/store', [SubAccountController::class, 'store'])->name('account.sub-account.store');
    Route::get('account/{id}/sub-account/{subId}/edit', [SubAccountController::class, 'edit'])->name('account.sub-account.edit');
    Route::put('account/{id}/sub-account/{subId}/update', [SubAccountController::class, 'update'])->name('account.sub-account.update');
    Route::delete('account/{id}/sub-account/{subId}/destroy', [SubAccountController::class, 'destroy'])->name('account.sub-account.destroy');
    Route::get('vendor', [VendorController::class, 'index'])->name('vendor.index');
    Route::get('vendor/create', [VendorController::class, 'create'])->name('vendor.create');
    Route::post('vendor/store', [VendorController::class, 'store'])->name('vendor.store');
    Route::get('vendor/{id}/show', [VendorController::class, 'show'])->name('vendor.show');
    Route::get('vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
    Route::get('purchase/create', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::get('purchase', [PurchaseController::class, 'index'])->name('purchase.index');
    Route::post('purchase/store', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('purchase/{id}/show', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::get('purchase/{id}/item/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('purchase/{id}/item/store', [ItemController::class, 'store'])->name('item.store');
    Route::post('purchase/{id}/updatestatus', [PurchaseController::class, 'updatestatus'])->name('purchase.updatestatus');
    Route::get('generalleadger', [GeneralleadgerController::class, 'index'])->name('generalleadger.index');
   Route::get('incomestatement', [IncomestatementController::class, 'index'])->name('incomestatement.index'); 
    Route::get('eva', [EvaController::class, 'index'])->name('eva.index');
Route::post('eva', [EvaController::class, 'calculate'])->name('eva.calculate');

});

require __DIR__.'/auth.php';
