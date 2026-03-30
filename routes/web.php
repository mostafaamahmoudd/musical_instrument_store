<?php

use App\Http\Controllers\Admin\BuilderController;
use App\Http\Controllers\Admin\InstrumentController as AdminInstrumentController;
use App\Http\Controllers\Admin\InstrumentFamilyController;
use App\Http\Controllers\Admin\InstrumentTypeController;
use App\Http\Controllers\Admin\WoodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Storefront\InstrumentController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsCustomer;
use Illuminate\Support\Facades\Route;

Route::get('/', [InstrumentController::class, 'home'])->name('home');

Route::get('/inventory', [InstrumentController::class, 'index'])
    ->name('storefront.instruments.index');

Route::get('/inventory/{instrument}', [InstrumentController::class, 'show'])
    ->name('storefront.instruments.show');

Route::middleware(['auth', 'verified', EnsureUserIsCustomer::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'verified', EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('instrument-families', InstrumentFamilyController::class);
    Route::resource('builders', BuilderController::class);
    Route::resource('instrument-types', InstrumentTypeController::class);
    Route::resource('woods', WoodController::class);
    Route::resource('instruments', AdminInstrumentController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
