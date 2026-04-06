<?php

namespace Database\Factories;

use App\Models\InstrumentFamily;
use App\Models\InstrumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstrumentType>
 */
class InstrumentTypeFactory extends Factory
{
    protected $model = InstrumentType::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'instrument_family_id' => InstrumentFamily::factory(),
            'name' => str($name)->title()->toString(),
            'slug' => str($name)->slug()->toString(),
        ];
    }
}
