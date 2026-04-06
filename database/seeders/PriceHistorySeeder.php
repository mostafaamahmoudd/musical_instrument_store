<?php

namespace Database\Seeders;

use App\Models\Instrument;
use App\Models\PriceHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class PriceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        if (! $admin) {
            return;
        }

        $instruments = Instrument::query()->take(5)->get();

        foreach ($instruments as $instrument) {
            $oldPrice = max(50, (float) $instrument->price - 150);

            PriceHistory::create([
                'instrument_id' => $instrument->id,
                'changed_by' => $admin->id,
                'old_price' => $oldPrice,
                'new_price' => $instrument->price,
            ]);
        }
    }
}
