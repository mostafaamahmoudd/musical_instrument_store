<?php

namespace App\Providers;

use App\Models\Builder;
use App\Models\Inquiry;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentType;
use App\Models\Reservation;
use App\Models\Wood;
use App\Observers\AuditableObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        
        Relation::enforceMorphMap([
            'instrument' => Instrument::class,
            'builder' => Builder::class,
            'wood' => Wood::class,
            'instrument_type' => InstrumentType::class,
            'instrument_family' => InstrumentFamily::class,
            'inquiry' => Inquiry::class,
            'reservation' => Reservation::class,
        ]);

        $observer = AuditableObserver::class;

        Instrument::observe($observer);
        Builder::observe($observer);
        Wood::observe($observer);
        InstrumentFamily::observe($observer);
        InstrumentType::observe($observer);
        Inquiry::observe($observer);
        Reservation::observe($observer);
    }
}
