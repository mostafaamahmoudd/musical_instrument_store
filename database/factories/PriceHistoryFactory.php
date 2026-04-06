<?php

namespace Database\Factories;

use App\Models\Instrument;
use App\Models\PriceHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceHistory>
 */
class PriceHistoryFactory extends Factory
{
    protected $model = PriceHistory::class;

    public function definition(): array
    {
        $oldPrice = $this->faker->randomFloat(2, 300, 2000);
        $newPrice = $oldPrice + $this->faker->randomFloat(2, 50, 500);

        return [
            'instrument_id' => Instrument::factory()->published(),
            'changed_by' => User::factory()->state(['type' => User::ADMIN_TYPE]),
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
        ];
    }
}
