<?php

namespace Database\Factories;

use App\Models\Instrument;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['type' => User::CUSTOMER_TYPE]),
            'instrument_id' => Instrument::factory()->published(),
            'status' => Reservation::PENDING,
            'reserved_until' => null,
            'notes' => $this->faker->optional()->sentence(10),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => Reservation::APPROVED,
            'reserved_until' => now()->addDays(5),
        ]);
    }
}
