<?php

namespace Database\Seeders;

use App\Models\InstrumentFamily;
use Illuminate\Database\Seeder;

class InstrumentFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $families = [
            ['name' => 'Strings', 'slug' => 'strings'],
            ['name' => 'Woodwind', 'slug' => 'woodwind'],
            ['name' => 'Brass', 'slug' => 'brass'],
            ['name' => 'Percussion', 'slug' => 'percussion'],
            ['name' => 'Keyboard', 'slug' => 'keyboard'],
        ];

        foreach ($families as $family) {
            InstrumentFamily::updateOrCreate(
                ['slug' => $family['slug']],
                ['name' => $family['name']]
            );
        }
    }
}
