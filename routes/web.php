<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Register as RegisterLivewire;
use App\Http\Controllers\{
    AccountController,
    DashboardController,
    EvaController,
    GeneralleadgerController,
    IncomeStatementController,
    ItemController,
    MasterProjectController,
    PurchaseController,
    RABController,
    RabWeeklyController,
    SubAccountController,
    UserController,
    VendorController,
    WbsController
};
use App\Livewire\Settings\{Appearance, Password, Profile};

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    // Settings
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

   // Master Project
Route::prefix('master-project')->name('master-projects.')->group(function () {
    Route::get('/', [MasterProjectController::class, 'index'])->name('index');
    Route::get('/create', [MasterProjectController::class, 'create'])->name('create');
    Route::post('/store', [MasterProjectController::class, 'store'])->name('store');
    Route::get('/{id}/show', [MasterProjectController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [MasterProjectController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [MasterProjectController::class, 'update'])->name('update');
    // Tambahkan route ini di dalam group route yang sudah ada
Route::post('/projects/{project}/progress/wbs', [MasterProjectController::class, 'storeProgressFromWbs'])
    ->name('project-progress.store-from-wbs');
    
    // Tambahan route untuk WBS progress
    Route::post('/{project}/progress/wbs', [MasterProjectController::class, 'storeProgressFromWbs'])
        ->name('progress.wbs.store');
});
Route::post('project-progress/{project}', [MasterProjectController::class, 'storeProgress'])->name('project-progress.store');
    // Account & Sub-Account
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->name('create');
        Route::post('/store', [AccountController::class, 'store'])->name('store');
        Route::get('/{id}/show', [AccountController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [AccountController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [AccountController::class, 'destroy'])->name('destroy');

        // Sub-account
        Route::get('/{id}/sub-account/create', [SubAccountController::class, 'create'])->name('sub-account.create');
        Route::post('/{id}/sub-account/store', [SubAccountController::class, 'store'])->name('sub-account.store');
        Route::get('/{id}/sub-account/{subId}/edit', [SubAccountController::class, 'edit'])->name('sub-account.edit');
        Route::put('/{id}/sub-account/{subId}/update', [SubAccountController::class, 'update'])->name('sub-account.update');
        Route::delete('/{id}/sub-account/{subId}/destroy', [SubAccountController::class, 'destroy'])->name('sub-account.destroy');
    });

    // Vendor
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/create', [VendorController::class, 'create'])->name('create');
        Route::post('/store', [VendorController::class, 'store'])->name('store');
        Route::get('/{id}/show', [VendorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [VendorController::class, 'edit'])->name('edit');
    });

Route::prefix('purchase')->name('purchase.')->group(function () {
    Route::get('/', [PurchaseController::class, 'index'])->name('index');
    Route::get('/create', [PurchaseController::class, 'create'])->name('create');
    Route::post('/', [PurchaseController::class, 'store'])->name('store');
    Route::get('/{id}', [PurchaseController::class, 'show'])->name('show');
    
    // Route untuk halaman items dan status - tetap di PurchaseController
    Route::get('/{id}/items', [PurchaseController::class, 'items'])->name('items');
    Route::post('/{id}/updatestatus', [PurchaseController::class, 'updatestatus'])->name('updatestatus');
});

// ✅ PERBAIKAN: Item routes dengan prefix purchase untuk konsistensi
Route::prefix('purchase')->name('purchase.')->group(function () {
    // Routes item menggunakan ItemController tapi tetap dengan prefix purchase
    Route::get('/{id}/item/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('/{id}/item/store', [ItemController::class, 'store'])->name('item.store');
    Route::get('/{purchaseId}/item/{itemId}/edit', [ItemController::class, 'edit'])->name('item.edit');
    Route::put('/{purchaseId}/item/{itemId}/update', [ItemController::class, 'update'])->name('item.update');
    Route::delete('/{purchaseId}/item/{itemId}/delete', [ItemController::class, 'destroy'])->name('item.delete');
});

    // General Ledger & Income Statement
    Route::get('generalleadger', [GeneralleadgerController::class, 'index'])->name('generalleadger.index');
    Route::get('incomestatement', [IncomeStatementController::class, 'index'])->name('incomestatement.index');

    // EVA
 Route::prefix('eva')->name('eva.')->group(function () {
    Route::get('/', [EvaController::class, 'index'])->name('index');
    Route::get('/create', [EvaController::class, 'create'])->name('create');
    Route::post('/store', [EvaController::class, 'store'])->name('store');
    Route::get('/{eva}', [EvaController::class, 'show'])->name('show');
    Route::post('/{eva}/notes', [EvaController::class, 'updateNotes'])->name('updateNotes');
    
    // Perbaikan route untuk update status - hapus duplikasi dan perbaiki penamaan
    Route::post('/{eva}/status', [EvaController::class, 'updateStatus'])
        ->name('updateStatus')
        ->middleware('auth');
});


    // RAB
 Route::prefix('rab')->group(function () {
    Route::get('/', [RABController::class, 'index'])->name('rab.index');
    Route::get('/create', [RABController::class, 'create'])->name('rab.create');
    Route::post('/', [RABController::class, 'store'])->name('rab.store');
});


// GET untuk menampilkan WBS berdasarkan proyek
// Route utama WBS (semua logika by project cukup lewat query ?project_id=...)
Route::prefix('wbs')->name('wbs.')->group(function () {
    Route::get('/', [WbsController::class, 'index'])->name('index');
    Route::get('/create', [WbsController::class, 'create'])->name('create');
    Route::post('/', [WbsController::class, 'store'])->name('store');
    Route::get('/{wbs}/edit', [WbsController::class, 'edit'])->name('edit');
    Route::put('/{wbs}', [WbsController::class, 'update'])->name('update');
    Route::delete('/{wbs}', [WbsController::class, 'destroy'])->name('destroy'); // ✅ ini harus {wbs}
});



    // WBS by Project
    Route::get('projects/{project}/wbs', [WbsController::class, 'byProject'])->name('wbs.byProject');
    Route::post('projects/{project}/wbs', [WbsController::class, 'storeByProject'])->name('wbs.storeByProject');
    Route::prefix('rab-weekly')->name('rab-weekly.')->group(function () {
    Route::get('/', [RabWeeklyController::class, 'index'])->name('index');
    Route::get('/create', [RabWeeklyController::class, 'create'])->name('create');
    Route::post('/', [RabWeeklyController::class, 'store'])->name('store');
});

});
Route::middleware('guest')->group(function () {
    Route::get('register', RegisterLivewire::class)->name('register');
});
Route::middleware(['auth'])->group(function () {
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
});
Route::get('/project/{id}/download-document', [MasterProjectController::class, 'downloadDocument'])
    ->name('project.document.download');
    Route::get('/project/{id}/stream-document', [MasterProjectController::class, 'streamDocument'])
    ->name('project.document.stream');
require __DIR__.'/auth.php';
