<?php

namespace Database\Seeders;

use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\User;
use App\Models\Wood;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $builders = Builder::all();
        $types = InstrumentType::with('instrumentFamily')->get();
        $woods = Wood::all();
        $admin = User::where('type', User::ADMIN_TYPE)->first();

        if ($builders->isEmpty() || $types->isEmpty()) {
            return;
        }

        $faker = fake();
        $createdBy = $admin?->id;

        for ($i = 0; $i < 20; $i++) {
            $type = $types->random();
            $builder = $builders->random();
            $backWood = $woods->isNotEmpty() && $faker->boolean(70) ? $woods->random() : null;
            $topWood = $woods->isNotEmpty() && $faker->boolean(70) ? $woods->random() : null;
            $year = $faker->numberBetween(1960, now()->year);

            $spec = InstrumentSpec::create([
                'instrument_family_id' => $type->instrument_family_id,
                'builder_id' => $builder->id,
                'instrument_type_id' => $type->id,
                'model' => $faker->optional(0.8)->words(2, true),
                'num_strings' => $faker->optional(0.7)->randomElement([4, 6, 7, 8, 12]),
                'back_wood_id' => $backWood?->id,
                'top_wood_id' => $topWood?->id,
                'style' => $faker->optional(0.6)->word(),
                'finish' => $faker->optional(0.6)->randomElement(['Gloss', 'Satin', 'Matte', 'Natural']),
                'description' => $faker->optional(0.5)->sentence(12),
            ]);

            Instrument::create([
                'instrument_spec_id' => $spec->id,
                'serial_number' => strtoupper($faker->unique()->bothify('SN####??')),
                'sku' => strtoupper($faker->unique()->bothify('SKU-####')),
                'price' => $faker->randomFloat(2, 199, 4999),
                'condition' => $faker->randomElement(Instrument::conditionTypes()),
                'stock_status' => $faker->randomElement(Instrument::stockStatus()),
                'year_made' => sprintf('%d-01-01', $year),
                'quantity' => $faker->numberBetween(1, 6),
                'featured' => $faker->boolean(20),
                'published_at' => $faker->boolean(80) ? $faker->dateTimeBetween('-2 years') : null,
                'created_by' => $createdBy,
                'updated_by' => $createdBy,
            ]);
        }
    }
}
