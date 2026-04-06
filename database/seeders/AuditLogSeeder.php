<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Instrument;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
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

        $instrument = Instrument::query()->first();

        if (! $instrument) {
            return;
        }

        AuditLog::create([
            'user_id' => $admin->id,
            'action' => AuditLog::UPDATED,
            'auditable_type' => $instrument->getMorphClass(),
            'auditable_id' => $instrument->id,
            'old_values' => ['price' => (float) $instrument->price - 100],
            'new_values' => ['price' => (float) $instrument->price],
        ]);
    }
}
