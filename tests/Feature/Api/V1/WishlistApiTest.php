<?php

namespace Tests\Feature\Api\V1;

use App\Models\Instrument;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WishlistApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_wishlist_endpoints_require_authentication(): void
    {
        $instrument = Instrument::factory()->published()->create();

        $this->getJson('/api/v1/wishlist')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->postJson("/api/v1/wishlist/{$instrument->id}")
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->deleteJson("/api/v1/wishlist/{$instrument->id}")
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_authenticated_customer_can_list_only_their_wishlist_items(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $instrumentA = Instrument::factory()->published()->create([
            'serial_number' => 'SN-WISHLIST-A',
        ]);
        $instrumentB = Instrument::factory()->published()->create([
            'serial_number' => 'SN-WISHLIST-B',
        ]);

        WishlistItem::factory()->create([
            'user_id' => $user->id,
            'instrument_id' => $instrumentA->id,
        ]);

        WishlistItem::factory()->create([
            'user_id' => $otherUser->id,
            'instrument_id' => $instrumentB->id,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/wishlist');

        $response->assertOk()
            ->assertJsonPath('message', 'Wishlist items retrieved successfully.')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.instrument.serial_number', 'SN-WISHLIST-A')
            ->assertJsonPath('meta.pagination.total', 1)
            ->assertJsonPath('meta.links.first', url('/api/v1/wishlist?page=1'));
    }

    public function test_store_creates_a_wishlist_item_only_once(): void
    {
        $user = User::factory()->create();
        $instrument = Instrument::factory()->published()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->withToken($token)
            ->postJson("/api/v1/wishlist/{$instrument->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Instrument added to your wishlist.');

        $this->withToken($token)
            ->postJson("/api/v1/wishlist/{$instrument->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Instrument added to your wishlist.');

        $this->assertSame(1, WishlistItem::query()
            ->where('user_id', $user->id)
            ->where('instrument_id', $instrument->id)
            ->count());
    }

    public function test_store_rejects_hidden_unpublished_and_future_published_instruments(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $hiddenInstrument = Instrument::factory()->hidden()->create();
        $draftInstrument = Instrument::factory()->create();
        $futureInstrument = Instrument::factory()->create([
            'published_at' => now()->addDay(),
        ]);

        $this->withToken($token)
            ->postJson("/api/v1/wishlist/{$hiddenInstrument->id}")
            ->assertNotFound();

        $this->withToken($token)
            ->postJson("/api/v1/wishlist/{$draftInstrument->id}")
            ->assertNotFound();

        $this->withToken($token)
            ->postJson("/api/v1/wishlist/{$futureInstrument->id}")
            ->assertNotFound();
    }

    public function test_destroy_removes_only_the_authenticated_users_wishlist_item(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $instrument = Instrument::factory()->published()->create();

        WishlistItem::factory()->create([
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
        ]);

        Sanctum::actingAs($otherUser);
        $this->deleteJson("/api/v1/wishlist/{$instrument->id}")
            ->assertNotFound();

        Sanctum::actingAs($user);
        $this->deleteJson("/api/v1/wishlist/{$instrument->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Instrument removed from your wishlist.');

        $this->assertDatabaseMissing('wishlist_items', [
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
        ]);
    }
}
