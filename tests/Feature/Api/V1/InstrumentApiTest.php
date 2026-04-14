<?php

namespace Tests\Feature\Api\V1;

use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\User;
use App\Models\Wood;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstrumentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_instrument_endpoints_require_authentication(): void
    {
        $this->getJson('/api/v1/instruments')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $instrument = Instrument::factory()->published()->create();

        $this->getJson("/api/v1/instruments/{$instrument->id}")
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_authenticated_customer_can_list_only_visible_instruments_with_filters(): void
    {
        Storage::fake('public');
        config(['media-library.disk_name' => 'public']);

        $customer = User::factory()->create();

        $matchingInstrument = Instrument::factory()
            ->published()
            ->create([
                'serial_number' => 'SN-MATCH',
                'price' => 2200.00,
            ]);
        $matchingInstrument->spec->update([
            'model' => 'Concert Custom',
            'finish' => 'Gloss',
        ]);
        $matchingInstrument->addMedia(UploadedFile::fake()->image('match.jpg'))
            ->toMediaCollection('gallery');

        Instrument::factory()
            ->published()
            ->create(['serial_number' => 'SN-OTHER', 'price' => 1200.00]);

        Instrument::factory()->hidden()->create(['serial_number' => 'SN-HIDDEN']);
        Instrument::factory()->create(['serial_number' => 'SN-DRAFT']);

        $token = $customer->createToken('auth_token')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/instruments?q=Concert&price_min=2000&sort=price_high_low');

        $response->assertOk()
            ->assertJsonPath('message', 'Customer instruments fetched successfully.')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.serial_number', 'SN-MATCH')
            ->assertJsonPath('data.0.spec.model', 'Concert Custom')
            ->assertJsonPath('data.0.images.0.file_name', 'match.jpg')
            ->assertJsonPath('meta.pagination.total', 1);
    }

    public function test_authenticated_customer_can_view_visible_instrument_details(): void
    {
        Storage::fake('public');
        config(['media-library.disk_name' => 'public']);

        [$family, $type, $builder, $topWood, $backWood] = $this->seedInstrumentDependencies();

        $customer = User::factory()->create();

        $instrument = Instrument::factory()
            ->published()
            ->create([
                'instrument_spec_id' => null,
                'serial_number' => 'SN-DETAIL',
                'sku' => 'SKU-DETAIL',
            ]);

        $spec = InstrumentSpec::create([
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => 'Master Grade',
            'num_strings' => 6,
            'back_wood_id' => $backWood->id,
            'top_wood_id' => $topWood->id,
            'style' => 'OM',
            'finish' => 'Satin',
            'description' => 'Responsive and balanced.',
        ]);

        $instrument->update([
            'instrument_spec_id' => $spec->id,
        ]);
        $instrument->refresh();

        $instrument->addMedia(UploadedFile::fake()->image('detail.jpg'))
            ->toMediaCollection('gallery');

        $token = $customer->createToken('auth_token')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson("/api/v1/instruments/{$instrument->id}");

        $response->assertOk()
            ->assertJsonPath('message', 'Customer instrument fetched successfully.')
            ->assertJsonPath('data.serial_number', 'SN-DETAIL')
            ->assertJsonPath('data.spec.family.name', 'Guitars')
            ->assertJsonPath('data.spec.type.name', 'Acoustic')
            ->assertJsonPath('data.spec.builder.name', 'North Workshop')
            ->assertJsonPath('data.spec.top_wood.name', 'Spruce')
            ->assertJsonPath('data.spec.back_wood.name', 'Rosewood')
            ->assertJsonPath('data.images.0.file_name', 'detail.jpg');
    }

    public function test_hidden_or_unpublished_instrument_details_are_not_accessible(): void
    {
        $customer = User::factory()->create();
        $token = $customer->createToken('auth_token')->plainTextToken;

        $hiddenInstrument = Instrument::factory()->hidden()->create();
        $draftInstrument = Instrument::factory()->create();

        $this->withToken($token)
            ->getJson("/api/v1/instruments/{$hiddenInstrument->id}")
            ->assertNotFound();

        $this->withToken($token)
            ->getJson("/api/v1/instruments/{$draftInstrument->id}")
            ->assertNotFound();
    }

    private function seedInstrumentDependencies(): array
    {
        $family = InstrumentFamily::create([
            'name' => 'Guitars',
            'slug' => 'guitars',
        ]);

        $type = InstrumentType::create([
            'instrument_family_id' => $family->id,
            'name' => 'Acoustic',
            'slug' => 'acoustic',
        ]);

        $builder = Builder::create([
            'name' => 'North Workshop',
            'slug' => 'north-workshop',
            'country' => 'US',
            'is_active' => true,
        ]);

        $topWood = Wood::create([
            'name' => 'Spruce',
            'slug' => 'spruce',
        ]);

        $backWood = Wood::create([
            'name' => 'Rosewood',
            'slug' => 'rosewood',
        ]);

        return [$family, $type, $builder, $topWood, $backWood];
    }
}
