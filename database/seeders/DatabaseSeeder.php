<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            InstrumentFamilySeeder::class,
            InstrumentTypeSeeder::class,
            BuilderSeeder::class,
            WoodSeeder::class,
            InstrumentSeeder::class,
            WishlistSeeder::class,
            InquirySeeder::class,
            ReservationSeeder::class,
            PriceHistorySeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
