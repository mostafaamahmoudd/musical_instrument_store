<?php

namespace Database\Seeders;

use App\Models\Instrument;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();

        if (! $customer) {
            return;
        }

        $instruments = Instrument::query()->ofVisible()->take(5)->get();

        foreach ($instruments as $instrument) {
            WishlistItem::firstOrCreate([
                'user_id' => $customer->id,
                'instrument_id' => $instrument->id,
            ]);
        }
    }
}
