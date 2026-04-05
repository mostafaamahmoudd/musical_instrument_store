<?php

namespace App\Observers;

use App\Models\Instrument;
use App\Models\PriceHistory;

class InstrumentObserver
{
    public function updated(Instrument $instrument): void
    {
        if (! $instrument->wasChanged('price')) {
            return;
        }

        $old = $instrument->getOriginal('price');
        $new = $instrument->price;

        if ($old === null || (string) $old === (string) $new) {
            return;
        }

        PriceHistory::create([
            'instrument_id' => $instrument->id,
            'old_price' => $old,
            'new_price' => $new,
            'changed_by' => auth()->id(),
        ]);
    }
}
