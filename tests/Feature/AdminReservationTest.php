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

class AdminReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reservations_index(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Cellos', 'Orchestral', 'Arc');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Arc Cello',
            'published_at' => now()->subDay(),
        ]);

        $admin = User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);

        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        Reservation::create([
            'user_id' => $customer->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
            'reserved_until' => null,
            'notes' => 'Please hold it.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reservations.index'))
            ->assertOk()
            ->assertViewIs('admin.reservations.index')
            ->assertSeeText('Arc Cello');
    }

    public function test_admin_can_approve_reservation_and_mark_instrument_reserved(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Harps', 'Concert', 'Resonance');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Resonance 9',
            'published_at' => now()->subDay(),
        ]);

        $admin = User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);

        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $reservation = Reservation::create([
            'user_id' => $customer->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
            'reserved_until' => null,
            'notes' => 'Pending request',
        ]);

        $reservedUntil = now()->addDays(5)->format('Y-m-d H:i:s');

        $this->actingAs($admin)
            ->patch(route('admin.reservations.update', $reservation), [
                'status' => Reservation::APPROVED,
                'reserved_until' => $reservedUntil,
                'notes' => 'Approved by admin',
            ])
            ->assertRedirect(route('admin.reservations.show', $reservation));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => Reservation::APPROVED,
            'notes' => 'Approved by admin',
        ]);

        $this->assertDatabaseHas('instruments', [
            'id' => $instrument->id,
            'stock_status' => Instrument::RESERVED,
        ]);
    }

    public function test_admin_rejecting_approved_reservation_releases_instrument_and_clears_reserved_until(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Pianos', 'Grand', 'North Stage');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'North Stage 3',
            'published_at' => now()->subDay(),
            'stock_status' => Instrument::RESERVED,
        ]);

        $admin = User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);

        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $reservation = Reservation::create([
            'user_id' => $customer->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::APPROVED,
            'reserved_until' => now()->addDays(2),
            'notes' => 'Approved request',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.reservations.update', $reservation), [
                'status' => Reservation::REJECTED,
                'reserved_until' => now()->addDay()->format('Y-m-d H:i:s'),
                'notes' => 'Rejected after review',
            ])
            ->assertRedirect(route('admin.reservations.show', $reservation));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => Reservation::REJECTED,
            'reserved_until' => null,
        ]);

        $this->assertDatabaseHas('instruments', [
            'id' => $instrument->id,
            'stock_status' => Instrument::AVAILABLE,
        ]);
    }

    public function test_admin_cannot_approve_second_reservation_for_same_instrument(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Basses', 'Electric', 'Signal Works');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Signal 5',
            'published_at' => now()->subDay(),
            'stock_status' => Instrument::RESERVED,
        ]);

        $admin = User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);

        $customerA = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $customerB = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        Reservation::create([
            'user_id' => $customerA->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::APPROVED,
            'reserved_until' => now()->addDays(4),
            'notes' => 'Already approved',
        ]);

        $pendingReservation = Reservation::create([
            'user_id' => $customerB->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
            'reserved_until' => null,
            'notes' => 'Waiting',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.reservations.show', $pendingReservation))
            ->patch(route('admin.reservations.update', $pendingReservation), [
                'status' => Reservation::APPROVED,
                'reserved_until' => now()->addDays(7)->format('Y-m-d H:i:s'),
                'notes' => 'Try to approve second request',
            ]);

        $response->assertRedirect(route('admin.reservations.show', $pendingReservation));
        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('reservations', [
            'id' => $pendingReservation->id,
            'status' => Reservation::PENDING,
        ]);
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
            'description' => $overrides['description'] ?? 'Reservation admin test instrument',
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
