<?php

namespace Tests\Feature;

use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StorefrontReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_reservation_request_for_available_instrument(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Guitars', 'Acoustic', 'Atlas');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Atlas A1',
            'published_at' => now()->subDay(),
        ]);

        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $this->actingAs($customer)
            ->get(route('storefront.reservations.create', $instrument))
            ->assertOk()
            ->assertViewIs('storefront.reservations.create')
            ->assertSeeText('Request a reservation');

        $response = $this->actingAs($customer)
            ->post(route('storefront.reservations.store', $instrument), [
                'notes' => 'Please contact me after 5pm.',
            ]);

        $response->assertRedirect(route('storefront.instruments.show', $instrument));

        $this->assertDatabaseHas('reservations', [
            'user_id' => $customer->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
            'notes' => 'Please contact me after 5pm.',
            'reserved_until' => null,
        ]);
    }

    public function test_customer_cannot_create_duplicate_active_reservation_for_same_instrument(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Mandolins', 'Flatback', 'Quiet Build');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Quiet 1',
            'published_at' => now()->subDay(),
        ]);

        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        Reservation::create([
            'user_id' => $customer->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
            'reserved_until' => null,
            'notes' => 'Existing request',
        ]);

        $response = $this->actingAs($customer)
            ->from(route('storefront.instruments.show', $instrument))
            ->post(route('storefront.reservations.store', $instrument), [
                'notes' => 'Duplicate request',
            ]);

        $response->assertRedirect(route('storefront.instruments.show', $instrument));
        $response->assertSessionHas('error', 'You already have an active reservation request for this instrument.');

        $this->assertSame(1, Reservation::query()->count());
    }

    public function test_reservations_index_shows_only_current_users_reservations_and_filters_by_status(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Violins', 'Classical', 'Bowline');

        $instrumentA = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Bowline 1',
            'published_at' => now()->subDay(),
        ]);

        $instrumentB = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Bowline 2',
            'published_at' => now()->subDay(),
        ]);

        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $otherCustomer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        Reservation::create([
            'user_id' => $customer->id,
            'instrument_id' => $instrumentA->id,
            'status' => Reservation::APPROVED,
            'reserved_until' => now()->addDays(3),
            'notes' => 'Approved',
        ]);

        Reservation::create([
            'user_id' => $customer->id,
            'instrument_id' => $instrumentB->id,
            'status' => Reservation::PENDING,
            'reserved_until' => null,
            'notes' => 'Pending',
        ]);

        Reservation::create([
            'user_id' => $otherCustomer->id,
            'instrument_id' => $instrumentB->id,
            'status' => Reservation::APPROVED,
            'reserved_until' => now()->addDay(),
            'notes' => 'Other user',
        ]);

        $response = $this->actingAs($customer)
            ->get(route('storefront.reservations.index', ['status' => Reservation::APPROVED]));

        $response->assertOk()
            ->assertViewIs('storefront.reservations.index')
            ->assertSeeText('Bowline 1')
            ->assertDontSeeText('Bowline 2')
            ->assertDontSeeText('Other user');
    }

    private function createCatalog(string $familyName, string $typeName, string $builderName): array
    {
        $family = InstrumentFamily::create([
            'name' => $familyName,
            'slug' => str($familyName)->slug()->toString(),
        ]);

        $type = InstrumentType::create([
            'instrument_family_id' => $family->id,
            'name' => $typeName,
            'slug' => str($familyName . ' ' . $typeName)->slug()->toString(),
        ]);

        $builder = Builder::create([
            'name' => $builderName,
            'slug' => str($builderName)->slug()->toString(),
            'country' => 'US',
            'is_active' => true,
        ]);

        return [$family, $type, $builder];
    }

    private function createInstrument(array $overrides = []): Instrument
    {
        $admin = User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);

        $family = $overrides['family'];
        $type = $overrides['type'];
        $builder = $overrides['builder'];

        $spec = InstrumentSpec::create([
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => $overrides['model'] ?? 'Model',
            'num_strings' => $overrides['num_strings'] ?? 6,
            'style' => $overrides['style'] ?? 'Standard',
            'finish' => $overrides['finish'] ?? 'Gloss',
            'description' => $overrides['description'] ?? 'Reservation test instrument',
        ]);

        $publishedAt = array_key_exists('published_at', $overrides)
            ? $overrides['published_at']
            : now()->subHour();

        $instrument = Instrument::create([
            'instrument_spec_id' => $spec->id,
            'serial_number' => $overrides['serial_number'] ?? 'SN-' . fake()->unique()->numerify('####'),
            'sku' => $overrides['sku'] ?? 'SKU-' . fake()->unique()->numerify('####'),
            'price' => $overrides['price'] ?? 999.99,
            'condition' => $overrides['condition'] ?? Instrument::NEW_CONDITION,
            'stock_status' => $overrides['stock_status'] ?? Instrument::AVAILABLE,
            'year_made' => $overrides['year_made'] ?? '2020-01-01',
            'quantity' => $overrides['quantity'] ?? 1,
            'featured' => $overrides['featured'] ?? false,
            'published_at' => $publishedAt,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        if (array_key_exists('published_at', $overrides) && $publishedAt === null) {
            DB::table('instruments')
                ->where('id', $instrument->id)
                ->update(['published_at' => null]);
            $instrument->refresh();
        }

        return $instrument;
    }
}
