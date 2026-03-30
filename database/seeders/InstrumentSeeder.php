<?php

namespace Database\Seeders;

use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\User;
use App\Models\Wood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        $seedImageDirectory = storage_path('app/private/seed-images');

        if ($builders->isEmpty() || $types->isEmpty()) {
            return;
        }

        if (! is_dir($seedImageDirectory)) {
            mkdir($seedImageDirectory, 0755, true);
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

            $instrument = Instrument::create([
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

            $this->attachSeedImages($instrument, $type, $builder, $seedImageDirectory);
        }
    }

    private function attachSeedImages(
        Instrument $instrument,
        InstrumentType $type,
        Builder $builder,
        string $directory
    ): void {
        $familyName = $type->instrumentFamily?->name ?? 'Instrument';
        $modelName = $instrument->spec?->model ?: 'Custom Build';

        foreach (['Showcase', 'Detail'] as $index => $variant) {
            $path = $this->generateSeedImage(
                $directory,
                $instrument,
                $familyName,
                $type->name,
                $builder->name,
                $modelName,
                $variant,
                $index
            );

            $instrument->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection('gallery');
        }
    }

    private function generateSeedImage(
        string $directory,
        Instrument $instrument,
        string $familyName,
        string $typeName,
        string $builderName,
        string $modelName,
        string $variant,
        int $index
    ): string {
        $width = 1600;
        $height = 1200;
        $image = imagecreatetruecolor($width, $height);

        $palettes = [
            [[28, 37, 65], [214, 142, 73], [249, 229, 181]],
            [[20, 61, 89], [83, 145, 101], [230, 213, 170]],
            [[70, 36, 92], [193, 103, 65], [242, 224, 193]],
            [[45, 55, 72], [192, 132, 95], [235, 225, 210]],
        ];

        $palette = $palettes[$instrument->id % count($palettes)];
        [$backgroundRgb, $accentRgb, $detailRgb] = $palette;

        $background = imagecolorallocate($image, ...$backgroundRgb);
        $accent = imagecolorallocate($image, ...$accentRgb);
        $detail = imagecolorallocate($image, ...$detailRgb);
        $white = imagecolorallocate($image, 248, 250, 252);
        $shadow = imagecolorallocatealpha($image, 15, 23, 42, 70);

        imagefilledrectangle($image, 0, 0, $width, $height, $background);

        for ($y = 0; $y < $height; $y++) {
            $mix = $y / max(1, $height - 1);
            $lineColor = imagecolorallocate(
                $image,
                (int) round($backgroundRgb[0] + (($accentRgb[0] - $backgroundRgb[0]) * $mix)),
                (int) round($backgroundRgb[1] + (($accentRgb[1] - $backgroundRgb[1]) * $mix)),
                (int) round($backgroundRgb[2] + (($accentRgb[2] - $backgroundRgb[2]) * $mix))
            );

            imageline($image, 0, $y, $width, $y, $lineColor);
        }

        imagefilledellipse($image, 1220, 250, 520, 520, $shadow);
        imagefilledellipse($image, 1260, 230, 500, 500, $detail);
        imagefilledrectangle($image, 1180, 390, 1320, 930, $detail);
        imagefilledellipse($image, 1250, 950, 150, 150, $detail);
        imagefilledrectangle($image, 1120, 430, 1380, 470, $accent);
        imagefilledrectangle($image, 1120, 520, 1380, 545, $accent);
        imagefilledrectangle($image, 1120, 600, 1380, 622, $accent);
        imagefilledrectangle($image, 1120, 680, 1380, 700, $accent);

        imagefilledrectangle($image, 90, 90, 760, 1030, $shadow);
        imagefilledrectangle($image, 80, 80, 750, 1020, imagecolorallocatealpha($image, 255, 255, 255, 90));

        imagestring($image, 5, 130, 150, strtoupper($familyName), $white);
        imagestring($image, 5, 130, 210, strtoupper($typeName . ' ' . $variant), $accent);
        imagestring($image, 4, 130, 320, Str::upper(Str::limit($builderName, 28, '')), $white);
        imagestring($image, 5, 130, 380, Str::upper(Str::limit($modelName, 26, '')), $white);
        imagestring($image, 4, 130, 520, 'CONDITION: ' . strtoupper($instrument->condition), $detail);
        imagestring($image, 4, 130, 570, 'PRICE: $' . number_format((float) $instrument->price, 2), $detail);
        imagestring($image, 4, 130, 620, 'SERIAL: ' . $instrument->serial_number, $detail);
        imagestring($image, 3, 130, 760, 'Seeded preview image generated locally for storefront demos.', $white);
        imagestring($image, 3, 130, 800, 'Each instrument receives two gallery images during seeding.', $white);

        $fileName = sprintf(
            '%s/%s-%d-%d.png',
            rtrim($directory, '/'),
            Str::slug($familyName . '-' . $typeName . '-' . $builderName),
            $instrument->id,
            $index
        );

        imagepng($image, $fileName);
        imagedestroy($image);

        return $fileName;
    }
}
