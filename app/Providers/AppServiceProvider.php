<?php

namespace App\Providers;

use App\Models\Instrument;
use App\Observers\InstrumentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Instrument::observe(InstrumentObserver::class);
    }
}
