<?php

namespace Database\Seeders;

use App\Models\Wood;
use Illuminate\Database\Seeder;

class WoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $woods = [
            ['name' => 'Spruce', 'slug' => 'spruce'],
            ['name' => 'Maple', 'slug' => 'maple'],
            ['name' => 'Mahogany', 'slug' => 'mahogany'],
            ['name' => 'Rosewood', 'slug' => 'rosewood'],
            ['name' => 'Ebony', 'slug' => 'ebony'],
            ['name' => 'Cedar', 'slug' => 'cedar'],
            ['name' => 'Alder', 'slug' => 'alder'],
            ['name' => 'Ash', 'slug' => 'ash'],
        ];

        foreach ($woods as $wood) {
            Wood::updateOrCreate(
                ['slug' => $wood['slug']],
                ['name' => $wood['name']]
            );
        }
    }
}
