<?php

use App\Http\Controllers\Admin\BuilderController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\InstrumentController as AdminInstrumentController;
use App\Http\Controllers\Admin\InstrumentFamilyController;
use App\Http\Controllers\Admin\InstrumentTypeController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\WoodController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('instrument-families', InstrumentFamilyController::class);
        Route::resource('builders', BuilderController::class);
        Route::resource('instrument-types', InstrumentTypeController::class);
        Route::resource('woods', WoodController::class);
        Route::resource('instruments', AdminInstrumentController::class);

        Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('/inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');

        Route::get('/reservations', [ReservationController::class, 'index'])
            ->name('reservations.index');
        Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])
            ->name('reservations.show');
        Route::patch('/reservations/{reservation}', [ReservationController::class, 'update'])
            ->name('reservations.update');
    });
