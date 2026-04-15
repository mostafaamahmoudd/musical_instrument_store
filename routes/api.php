<?php

use App\Http\Controllers\Api\V1\Auth\LoginCustomerController;
use App\Http\Controllers\Api\V1\Auth\LogoutCustomerController;
use App\Http\Controllers\Api\V1\Auth\MeCustomerController;
use App\Http\Controllers\Api\V1\Auth\RegisterCustomerController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\InquiryController;
use App\Http\Controllers\Api\V1\InstrumentController;
use App\Http\Controllers\Api\V1\WishlistController;
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
        Route::post('/instruments/{instrument}/inquiries', [InquiryController::class, 'store'])
            ->name('instruments.inquiries.store');

        // wishlist routes
        Route::get('/wishlist', [WishlistController::class, 'index'])
            ->name('wishlist.index');
        Route::post('/wishlist/{instrument}', [WishlistController::class, 'store'])
            ->name('wishlist.store');
        Route::delete('/wishlist/{instrument}', [WishlistController::class, 'destroy'])
            ->name('wishlist.destroy');

        // inquiry routes
        Route::get('/inquiries', [InquiryController::class, 'index'])
            ->name('inquiries.index');
        Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])
            ->name('inquiries.show');

    });
});
