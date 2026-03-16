<?php

namespace Database\Seeders;

use App\Models\InstrumentFamily;
use App\Models\InstrumentType;
use Illuminate\Database\Seeder;

class InstrumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typesByFamily = [
            'strings' => [
                ['name' => 'Violin', 'slug' => 'violin'],
                ['name' => 'Viola', 'slug' => 'viola'],
                ['name' => 'Cello', 'slug' => 'cello'],
                ['name' => 'Double Bass', 'slug' => 'double-bass'],
                ['name' => 'Acoustic Guitar', 'slug' => 'acoustic-guitar'],
                ['name' => 'Electric Guitar', 'slug' => 'electric-guitar'],
                ['name' => 'Harp', 'slug' => 'harp'],
            ],
            'woodwind' => [
                ['name' => 'Flute', 'slug' => 'flute'],
                ['name' => 'Clarinet', 'slug' => 'clarinet'],
                ['name' => 'Oboe', 'slug' => 'oboe'],
                ['name' => 'Bassoon', 'slug' => 'bassoon'],
                ['name' => 'Saxophone', 'slug' => 'saxophone'],
            ],
            'brass' => [
                ['name' => 'Trumpet', 'slug' => 'trumpet'],
                ['name' => 'Trombone', 'slug' => 'trombone'],
                ['name' => 'French Horn', 'slug' => 'french-horn'],
                ['name' => 'Tuba', 'slug' => 'tuba'],
            ],
            'percussion' => [
                ['name' => 'Snare Drum', 'slug' => 'snare-drum'],
                ['name' => 'Bass Drum', 'slug' => 'bass-drum'],
                ['name' => 'Timpani', 'slug' => 'timpani'],
                ['name' => 'Xylophone', 'slug' => 'xylophone'],
                ['name' => 'Cymbals', 'slug' => 'cymbals'],
            ],
            'keyboard' => [
                ['name' => 'Piano', 'slug' => 'piano'],
                ['name' => 'Upright Piano', 'slug' => 'upright-piano'],
                ['name' => 'Grand Piano', 'slug' => 'grand-piano'],
                ['name' => 'Organ', 'slug' => 'organ'],
                ['name' => 'Digital Piano', 'slug' => 'digital-piano'],
            ],
        ];

        foreach ($typesByFamily as $familySlug => $types) {
            $family = InstrumentFamily::where('slug', $familySlug)->first();

            if (! $family) {
                continue;
            }

            foreach ($types as $type) {
                InstrumentType::updateOrCreate(
                    ['slug' => $type['slug']],
                    [
                        'instrument_family_id' => $family->id,
                        'name' => $type['name'],
                    ]
                );
            }
        }
    }
}
