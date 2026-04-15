<?php

use App\Http\Controllers\Api\V1\Auth\LoginCustomerController;
use App\Http\Controllers\Api\V1\Auth\LogoutCustomerController;
use App\Http\Controllers\Api\V1\Auth\MeCustomerController;
use App\Http\Controllers\Api\V1\Auth\RegisterCustomerController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\InstrumentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/health', HealthController::class)->name('health');

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', RegisterCustomerController::class)->name('register');
        Route::post('/login', LoginCustomerController::class)->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', MeCustomerController::class)->name('me');
            Route::post('/logout', LogoutCustomerController::class)->name('logout');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        // instrument routes
        Route::get('/instruments', [InstrumentController::class, 'index'])
            ->name('instruments.index');
        Route::get('/instruments/{instrument}', [InstrumentController::class, 'show'])
            ->name('instruments.show');

        // wishlist routes
        Route::get('/wishlist', [\App\Http\Controllers\Api\V1\WishlistController::class, 'index'])
            ->name('wishlist.index');
        Route::post('/wishlist/{instrument}', [\App\Http\Controllers\Api\V1\WishlistController::class, 'store'])
            ->name('wishlist.store');
        Route::delete('/wishlist/{instrument}', [\App\Http\Controllers\Api\V1\WishlistController::class, 'destroy'])
            ->name('wishlist.destroy');
    });
});
