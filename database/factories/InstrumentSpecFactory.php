<?php

namespace Database\Factories;

use App\Models\Builder;
use App\Models\InstrumentFamily;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\Wood;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstrumentSpec>
 */
class InstrumentSpecFactory extends Factory
{
    protected $model = InstrumentSpec::class;

    public function definition(): array
    {
        $family = InstrumentFamily::factory();

        return [
            'instrument_family_id' => $family,
            'builder_id' => Builder::factory(),
            'instrument_type_id' => InstrumentType::factory()->for($family),
            'model' => $this->faker->optional(0.8)->words(2, true),
            'num_strings' => $this->faker->optional(0.7)->randomElement([4, 6, 7, 8, 12]),
            'back_wood_id' => $this->faker->boolean(70) ? Wood::factory() : null,
            'top_wood_id' => $this->faker->boolean(70) ? Wood::factory() : null,
            'style' => $this->faker->optional(0.6)->word(),
            'finish' => $this->faker->optional(0.6)->randomElement(['Gloss', 'Satin', 'Matte', 'Natural']),
            'description' => $this->faker->optional(0.5)->sentence(12),
        ];
    }
}
