<?php

namespace Database\Factories;

use App\Models\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Builder>
 */
class BuilderFactory extends Factory
{
    protected $model = Builder::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'country' => $this->faker->countryCode(),
            'is_active' => true,
        ];
    }
}
