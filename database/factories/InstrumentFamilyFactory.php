<?php

namespace Database\Factories;

use App\Models\InstrumentFamily;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstrumentFamily>
 */
class InstrumentFamilyFactory extends Factory
{
    protected $model = InstrumentFamily::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => str($name)->title()->toString(),
            'slug' => str($name)->slug()->toString(),
        ];
    }
}
