<?php

namespace Database\Factories;

use App\Models\Inquiry;
use App\Models\Instrument;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['type' => User::CUSTOMER_TYPE]),
            'assigned_admin_id' => null,
            'instrument_id' => Instrument::factory()->published(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->optional()->e164PhoneNumber(),
            'subject' => $this->faker->optional()->sentence(4),
            'message' => $this->faker->paragraph(),
            'status' => Inquiry::NEW,
        ];
    }
}
