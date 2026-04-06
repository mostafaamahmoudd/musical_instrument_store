<?php

namespace Database\Factories;

use App\Models\Wood;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Wood>
 */
class WoodFactory extends Factory
{
    protected $model = Wood::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => str($name)->title()->toString(),
            'slug' => str($name)->slug()->toString(),
        ];
    }
}
