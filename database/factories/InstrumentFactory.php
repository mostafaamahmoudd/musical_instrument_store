<?php

namespace Database\Factories;

use App\Models\Instrument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Instrument>
 */
class InstrumentFactory extends Factory
{
    protected $model = Instrument::class;

    public function definition(): array
    {
        return [
            'serial_number' => $this->faker->unique()->bothify('SN-####-????'),
            'sku' => $this->faker->optional()->bothify('SKU-####'),
            'instrument_spec_id' => null,
            'created_by' => null,
            'updated_by' => null,
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'condition' => Instrument::NEW_CONDITION,
            'stock_status' => Instrument::AVAILABLE,
            'year_made' => $this->faker->optional()->date(),
            'quantity' => $this->faker->numberBetween(1, 25),
            'featured' => false,
            'published_at' => null,
        ];
    }
}
