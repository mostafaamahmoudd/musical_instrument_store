<?php

use App\Models\Instrument;

if (! function_exists('ensureReservable')) {
    function ensureReservable(Instrument $instrument): void
    {
        abort_unless(
            $instrument->published_at && $instrument->stock_status === 'available',
            404
        );
    }
}
