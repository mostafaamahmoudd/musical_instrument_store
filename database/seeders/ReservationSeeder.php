<?php

namespace Database\Seeders;

use App\Models\Instrument;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
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

        $available = Instrument::query()
            ->ofVisible()
            ->where('stock_status', Instrument::AVAILABLE)
            ->take(3)
            ->get();

        if ($available->isEmpty()) {
            return;
        }

        $statuses = [
            Reservation::PENDING,
            Reservation::APPROVED,
            Reservation::REJECTED,
        ];

        foreach ($available as $index => $instrument) {
            $status = $statuses[$index % count($statuses)];

            Reservation::create([
                'user_id' => $customer->id,
                'instrument_id' => $instrument->id,
                'status' => $status,
                'reserved_until' => $status === Reservation::APPROVED
                    ? now()->addDays(5)
                    : null,
                'notes' => $status === Reservation::PENDING
                    ? 'Customer is flexible on pickup dates.'
                    : null,
            ]);

            if ($status === Reservation::APPROVED) {
                $instrument->update([
                    'stock_status' => Instrument::RESERVED,
                ]);
            }
        }
    }
}
