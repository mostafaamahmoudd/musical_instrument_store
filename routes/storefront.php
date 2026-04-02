<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Storefront\InstrumentController;
use App\Http\Controllers\Storefront\InquiryController as StorefrontInquiryController;
use App\Http\Controllers\Storefront\WishlistController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('storefront.wishlist.index');

    Route::post('/wishlist/{instrument}', [WishlistController::class, 'store'])
        ->name('storefront.wishlist.store');

    Route::delete('/wishlist/{instrument}', [WishlistController::class, 'destroy'])
        ->name('storefront.wishlist.destroy');

    Route::get('/inquiries', [StorefrontInquiryController::class, 'index'])
        ->name('storefront.inquiries.index');

    Route::get('/inquiries/{inquiry}', [StorefrontInquiryController::class, 'show'])
        ->name('storefront.inquiries.show');

    Route::get('/inventory/{instrument}/inquiries/create', [StorefrontInquiryController::class, 'create'])
        ->name('storefront.inquiries.create');

    Route::post('/inventory/{instrument}/inquiries', [StorefrontInquiryController::class, 'store'])
        ->name('storefront.inquiries.store');
});
