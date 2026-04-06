<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\Instrument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        $instrument = Instrument::factory()->create();

        return [
            'user_id' => User::factory()->state(['type' => User::ADMIN_TYPE]),
            'action' => $this->faker->randomElement([
                AuditLog::CREATED,
                AuditLog::UPDATED,
                AuditLog::DELETED,
            ]),
            'auditable_type' => $instrument->getMorphClass(),
            'auditable_id' => $instrument->id,
            'old_values' => ['price' => 1000],
            'new_values' => ['price' => 1200],
        ];
    }
}
