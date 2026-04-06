<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Instrument;
use App\Models\User;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();

        if (! $customer) {
            return;
        }

        $instruments = Instrument::query()->ofVisible()->take(4)->get();

        if ($instruments->isEmpty()) {
            return;
        }

        $statuses = Inquiry::statuses();

        foreach ($instruments as $index => $instrument) {
            Inquiry::create([
                'user_id' => $customer->id,
                'assigned_admin_id' => $index % 2 === 0 ? $admin?->id : null,
                'instrument_id' => $instrument->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'subject' => 'Question about ' . ($instrument->spec?->model ?? 'this instrument'),
                'message' => 'Could you share availability and shipping details?',
                'status' => $statuses[$index % count($statuses)],
            ]);
        }
    }
}
