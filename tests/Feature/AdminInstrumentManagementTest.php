<?php

namespace Tests\Feature;

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

class AdminInstrumentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_instrument_with_images(): void
    {
        Storage::fake('public');
        config(['media-library.disk_name' => 'public']);

        [$family, $type, $builder, $wood] = $this->seedInstrumentDependencies();
        $admin = $this->adminUser();

        $payload = [
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => 'Model X',
            'num_strings' => 6,
            'back_wood_id' => $wood->id,
            'top_wood_id' => $wood->id,
            'style' => 'Dreadnought',
            'finish' => 'Satin',
            'description' => 'Test instrument',
            'serial_number' => 'SN-1000',
            'sku' => 'SKU-1000',
            'price' => 1999.99,
            'condition' => Instrument::NEW_CONDITION,
            'stock_status' => Instrument::AVAILABLE,
            'year_made' => 2019,
            'quantity' => 2,
            'featured' => true,
            'published_at' => now()->toDateTimeString(),
            'images' => [
                UploadedFile::fake()->image('guitar.jpg', 1200, 800),
            ],
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.instruments.store'), $payload);

        $response->assertRedirect(route('admin.instruments.index'));

        $instrument = Instrument::first();
        $this->assertNotNull($instrument);
        $this->assertDatabaseHas('instruments', [
            'serial_number' => 'SN-1000',
            'year_made' => '2019-01-01 00:00:00',
        ]);

        $this->assertCount(1, $instrument->getMedia('gallery'));
    }

    public function test_guest_cannot_access_instrument_pages(): void
    {
        $this->get(route('admin.instruments.index'))
            ->assertRedirect(route('login'));
        $this->get(route('admin.instruments.create'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_instrument_pages(): void
    {
        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $this->actingAs($customer)
            ->get(route('admin.instruments.index'))
            ->assertStatus(403);
    }

    public function test_admin_can_view_index_and_create_pages(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->get(route('admin.instruments.index'))
            ->assertOk()
            ->assertViewIs('admin.instruments.index');

        $this->actingAs($admin)
            ->get(route('admin.instruments.create'))
            ->assertOk()
            ->assertViewIs('admin.instruments.create');
    }

    public function test_admin_can_view_edit_page(): void
    {
        [$family, $type, $builder] = $this->seedInstrumentDependencies();
        $admin = $this->adminUser();

        $spec = InstrumentSpec::create([
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => 'Edit Model',
        ]);

        $instrument = Instrument::create([
            'instrument_spec_id' => $spec->id,
            'serial_number' => 'SN-EDIT',
            'sku' => 'SKU-EDIT',
            'price' => 999.99,
            'condition' => Instrument::NEW_CONDITION,
            'stock_status' => Instrument::AVAILABLE,
            'year_made' => '2020-01-01',
            'quantity' => 1,
            'featured' => false,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.instruments.edit', $instrument))
            ->assertOk()
            ->assertViewIs('admin.instruments.edit');
    }

    public function test_admin_can_update_instrument_and_manage_images(): void
    {
        Storage::fake('public');
        config(['media-library.disk_name' => 'public']);

        [$family, $type, $builder, $wood] = $this->seedInstrumentDependencies();
        $admin = $this->adminUser();

        $spec = InstrumentSpec::create([
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => 'Initial Model',
        ]);

        $instrument = Instrument::create([
            'instrument_spec_id' => $spec->id,
            'serial_number' => 'SN-2000',
            'sku' => 'SKU-2000',
            'price' => 1499.50,
            'condition' => Instrument::USED_CONDITION,
            'stock_status' => Instrument::AVAILABLE,
            'year_made' => '2010-01-01',
            'quantity' => 1,
            'featured' => false,
            'published_at' => null,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $instrument->addMedia(UploadedFile::fake()->image('old.jpg', 800, 600))
            ->toMediaCollection('gallery');

        $oldMediaId = $instrument->getFirstMedia('gallery')->id;

        $payload = [
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => 'Updated Model',
            'num_strings' => 7,
            'back_wood_id' => $wood->id,
            'top_wood_id' => $wood->id,
            'style' => 'OM',
            'finish' => 'Gloss',
            'description' => 'Updated description',
            'serial_number' => 'SN-2000-UPDATED',
            'sku' => 'SKU-2000-UPDATED',
            'price' => 1750.00,
            'condition' => Instrument::VINTAGE_CONDITION,
            'stock_status' => Instrument::RESERVED,
            'year_made' => 1988,
            'quantity' => 3,
            'featured' => true,
            'published_at' => now()->toDateTimeString(),
            'delete_media' => [$oldMediaId],
            'images' => [
                UploadedFile::fake()->image('new.jpg', 1200, 900),
            ],
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.instruments.update', $instrument), $payload);

        $response->assertRedirect(route('admin.instruments.index'));

        $instrument->refresh();
        $this->assertDatabaseMissing('media', ['id' => $oldMediaId]);
        $this->assertCount(1, $instrument->getMedia('gallery'));
        $this->assertDatabaseHas('instruments', [
            'serial_number' => 'SN-2000-UPDATED',
            'year_made' => '1988-01-01 00:00:00',
        ]);
    }

    public function test_admin_can_delete_instrument(): void
    {
        [$family, $type, $builder] = $this->seedInstrumentDependencies();
        $admin = $this->adminUser();

        $spec = InstrumentSpec::create([
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => 'Delete Model',
        ]);

        $instrument = Instrument::create([
            'instrument_spec_id' => $spec->id,
            'serial_number' => 'SN-DELETE',
            'sku' => 'SKU-DELETE',
            'price' => 799.00,
            'condition' => Instrument::USED_CONDITION,
            'stock_status' => Instrument::AVAILABLE,
            'year_made' => '2018-01-01',
            'quantity' => 1,
            'featured' => false,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.instruments.destroy', $instrument));

        $response->assertRedirect(route('admin.instruments.index'));
        $this->assertDatabaseMissing('instruments', [
            'id' => $instrument->id,
        ]);
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
            'name' => 'Test Builder',
            'slug' => 'test-builder',
            'country' => 'US',
            'is_active' => true,
        ]);

        $wood = Wood::create([
            'name' => 'Mahogany',
            'slug' => 'mahogany',
        ]);

        return [$family, $type, $builder, $wood];
    }

    private function adminUser(): User
    {
        return User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);
    }
}
