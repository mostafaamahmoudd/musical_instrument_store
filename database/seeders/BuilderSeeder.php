<?php

namespace Database\Seeders;

use App\Models\Builder;
use Illuminate\Database\Seeder;

class BuilderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $builders = [
            ['name' => 'Fender', 'slug' => 'fender', 'country' => 'United States', 'is_active' => true],
            ['name' => 'Gibson', 'slug' => 'gibson', 'country' => 'United States', 'is_active' => true],
            ['name' => 'Yamaha', 'slug' => 'yamaha', 'country' => 'Japan', 'is_active' => true],
            ['name' => 'Steinway & Sons', 'slug' => 'steinway-and-sons', 'country' => 'United States', 'is_active' => true],
            ['name' => 'C. F. Martin & Co.', 'slug' => 'martin', 'country' => 'United States', 'is_active' => true],
            ['name' => 'Selmer', 'slug' => 'selmer', 'country' => 'France', 'is_active' => true],
            ['name' => 'Conn', 'slug' => 'conn', 'country' => 'United States', 'is_active' => true],
            ['name' => 'Roland', 'slug' => 'roland', 'country' => 'Japan', 'is_active' => true],
        ];

        foreach ($builders as $builder) {
            Builder::updateOrCreate(
                ['slug' => $builder['slug']],
                [
                    'name' => $builder['name'],
                    'country' => $builder['country'],
                    'is_active' => $builder['is_active'],
                ]
            );
        }
    }
}
