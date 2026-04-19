<?php

namespace Tests\Feature\Api\V1;

use App\Models\Instrument;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReservationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function customer(): User
    {
        return User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
            'is_active' => true,
        ]);
    }

    protected function availableInstrument(): Instrument
    {
        return Instrument::factory()->create([
            'published_at' => now(),
            'stock_status' => 'available',
        ]);
    }

    protected function hiddenInstrument(): Instrument
    {
        return Instrument::factory()->create([
            'published_at' => null,
            'stock_status' => 'hidden',
        ]);
    }

    public function test_guest_cannot_list_reservations(): void
    {
        $this->getJson('/api/v1/reservations')
            ->assertUnauthorized();
    }

    public function test_guest_cannot_show_reservation(): void
    {
        $reservation = Reservation::factory()->create();

        $this->getJson("/api/v1/reservations/{$reservation->id}")
            ->assertUnauthorized();
    }

    public function test_guest_cannot_create_reservation(): void
    {
        $instrument = $this->availableInstrument();

        $this->postJson("/api/v1/instruments/{$instrument->id}/reservations", [
            'notes' => 'Please reserve this for me.',
        ])->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_only_their_own_reservations(): void
    {
        $user = $this->customer();
        $otherUser = $this->customer();

        $ownReservation = Reservation::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherReservation = Reservation::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/reservations')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $ownReservation->id,
            ])
            ->assertJsonMissing([
                'id' => $otherReservation->id,
            ]);
    }

    public function test_authenticated_user_can_view_any_reservation_by_id_with_current_controller_behavior(): void
    {
        $user = $this->customer();
        $otherUser = $this->customer();

        $reservation = Reservation::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        Sanctum::actingAs($user);

        $this->getJson("/api/v1/reservations/{$reservation->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $reservation->id);
    }

    public function test_authenticated_user_can_create_reservation_for_available_published_instrument(): void
    {
        $user = $this->customer();
        $instrument = $this->availableInstrument();

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/v1/instruments/{$instrument->id}/reservations", [
            'notes' => 'Please reserve this instrument.',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', Reservation::PENDING)
            ->assertJsonPath('data.notes', 'Please reserve this instrument.');

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
            'notes' => 'Please reserve this instrument.',
        ]);
    }

    public function test_authenticated_user_can_create_reservation_without_notes(): void
    {
        $user = $this->customer();
        $instrument = $this->availableInstrument();

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/v1/instruments/{$instrument->id}/reservations", []);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', Reservation::PENDING);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
        ]);
    }

    public function test_create_reservation_fails_for_non_reservable_instrument(): void
    {
        $user = $this->customer();
        $instrument = $this->hiddenInstrument();

        Sanctum::actingAs($user);

        $this->postJson("/api/v1/instruments/{$instrument->id}/reservations", [
            'notes' => 'Please reserve this instrument.',
        ])->assertNotFound();
    }

    public function test_create_reservation_fails_when_notes_are_too_long(): void
    {
        $user = $this->customer();
        $instrument = $this->availableInstrument();

        Sanctum::actingAs($user);

        $this->postJson("/api/v1/instruments/{$instrument->id}/reservations", [
            'notes' => str_repeat('a', 1001),
        ])->assertUnprocessable();
    }

    public function test_user_cannot_create_duplicate_pending_reservation_for_same_instrument(): void
    {
        $user = $this->customer();
        $instrument = $this->availableInstrument();

        Reservation::factory()->create([
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::PENDING,
        ]);

        Sanctum::actingAs($user);

        $this->postJson("/api/v1/instruments/{$instrument->id}/reservations", [
            'notes' => 'Another reservation attempt.',
        ])->assertStatus(400);
    }

    public function test_user_cannot_create_duplicate_approved_reservation_for_same_instrument(): void
    {
        $user = $this->customer();
        $instrument = $this->availableInstrument();

        Reservation::factory()->create([
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::APPROVED,
        ]);

        Sanctum::actingAs($user);

        $this->postJson("/api/v1/instruments/{$instrument->id}/reservations", [
            'notes' => 'Another reservation attempt.',
        ])->assertStatus(400);
    }

    public function test_user_can_create_new_reservation_if_previous_one_is_rejected(): void
    {
        $user = $this->customer();
        $instrument = $this->availableInstrument();

        Reservation::factory()->create([
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
            'status' => Reservation::REJECTED,
        ]);

        Sanctum::actingAs($user);

        $this->postJson("/api/v1/instruments/{$instrument->id}/reservations", [
            'notes' => 'Try again after rejection.',
        ])->assertOk();

        $this->assertEquals(
            2,
            Reservation::where('user_id', $user->id)
                ->where('instrument_id', $instrument->id)
                ->count()
        );
    }

    public function test_index_can_filter_by_status(): void
    {
        $user = $this->customer();

        $pending = Reservation::factory()->create([
            'user_id' => $user->id,
            'status' => Reservation::PENDING,
        ]);

        $approved = Reservation::factory()->create([
            'user_id' => $user->id,
            'status' => Reservation::APPROVED,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/reservations?status='.Reservation::PENDING)
            ->assertOk()
            ->assertJsonFragment([
                'id' => $pending->id,
            ])
            ->assertJsonMissing([
                'id' => $approved->id,
            ]);
    }
}
