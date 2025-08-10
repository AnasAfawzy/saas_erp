<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;


use App\Livewire\AccountLinkingManager;

Route::get('/', function () {
    return view('auth.tabler-login');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Language routes
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/language/current', [LanguageController::class, 'current'])->name('language.current');

// Master Data routes
Route::middleware(['auth', 'verified'])->prefix('master-data')->name('master-data.')->group(function () {
    // Branches main route - using Livewire
    Route::get('/branches', function () {
        return view('master-data.branches.livewire');
    })->name('branches.index');

    // Additional branches routes for API/legacy support
    Route::resource('branches', BranchController::class)->except(['index']);
    Route::get('/branches/data/list', [BranchController::class, 'getData'])->name('branches.data');
    Route::patch('/branches/{branch}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggle-status');
    Route::get('/branches/active/list', [BranchController::class, 'getActiveList'])->name('branches.active-list');

    // Units main route - using Livewire
    Route::get('/units', function () {
        return view('master-data.units.livewire');
    })->name('units');

    // Categories main route - using Livewire
    Route::get('/categories', function () {
        return view('master-data.categories.livewire');
    })->name('categories');

    // Brands main route - using Livewire
    Route::get('/brands', function () {
        return view('master-data.brands.livewire');
    })->name('brands');


    Route::get('/products', function () {
        return view('master-data.products.livewire');
    })->name('products');
    Route::get('/customers', function () {
        return view('master-data.customers.livewire');
    })->name('customers');

    Route::get('/suppliers', function () {
        return view('master-data.suppliers.livewire');
    })->name('suppliers');

    Route::get('/warehouses', function () {
        return view('master-data.warehouses.livewire');
    })->name('warehouses');
});

// Accounting Routes
Route::middleware(['auth', 'verified'])->prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/accounts', function () {
        return view('accounting.accounts.livewire');
    })->name('accounts');

    // إدارة ربط الحسابات
    // Account Linking manual UI route
    Route::get('/account-linking', function () {
        return view('master-data.account_linking.livewire');
    })->name('account-linking');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Company settings routes
    Route::get('/company-settings', [CompanyController::class, 'settings'])->name('company.settings');
    Route::post('/company-settings', [CompanyController::class, 'updateSettings'])->name('company.settings.update');
    Route::post('/company-settings/upload-logo', [CompanyController::class, 'uploadLogo'])->name('company.settings.upload-logo');
    Route::delete('/company-settings/delete-logo', [CompanyController::class, 'deleteLogo'])->name('company.settings.delete-logo');
    Route::get('/company-settings/api', [CompanyController::class, 'getSettings'])->name('company.settings.api');
    Route::post('/company-settings/reset', [CompanyController::class, 'resetSettings'])->name('company.settings.reset');
    Route::post('/company-settings/format-number', [CompanyController::class, 'formatNumber'])->name('company.settings.format-number');
    Route::post('/company-settings/format-currency', [CompanyController::class, 'formatCurrency'])->name('company.settings.format-currency');
});

require __DIR__ . '/auth.php';
