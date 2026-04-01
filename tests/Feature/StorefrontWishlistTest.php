<?php

namespace Tests\Feature;

use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StorefrontWishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_only_current_users_wishlist_items(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Guitars', 'Acoustic', 'Atlas');

        $instrumentA = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Atlas A',
            'published_at' => now()->subDay(),
        ]);

        $instrumentB = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Atlas B',
            'published_at' => now()->subDay(),
        ]);

        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        WishlistItem::create([
            'user_id' => $user->id,
            'instrument_id' => $instrumentA->id,
        ]);

        WishlistItem::create([
            'user_id' => $otherUser->id,
            'instrument_id' => $instrumentB->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('storefront.wishlist.index'));

        $response->assertOk()
            ->assertViewIs('storefront.wishlist.index')
            ->assertSeeText('Atlas A')
            ->assertDontSeeText('Atlas B');
    }

    public function test_store_creates_wishlist_item_once(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Violins', 'Classical', 'Bowline');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Bowline 1',
            'published_at' => now()->subDay(),
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)->post(route('storefront.wishlist.store', $instrument))
            ->assertRedirect();

        $this->actingAs($user)->post(route('storefront.wishlist.store', $instrument))
            ->assertRedirect();

        $this->assertSame(1, WishlistItem::where('user_id', $user->id)
            ->where('instrument_id', $instrument->id)
            ->count());
    }

    public function test_store_returns_404_for_hidden_or_unpublished_instruments(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Mandolins', 'Flatback', 'Quiet Build');

        $hiddenInstrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Hidden',
            'stock_status' => Instrument::HIDDEN,
            'published_at' => now()->subDay(),
        ]);

        $draftInstrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Draft',
            'published_at' => null,
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('storefront.wishlist.store', $hiddenInstrument))
            ->assertNotFound();

        $this->actingAs($user)
            ->post(route('storefront.wishlist.store', $draftInstrument))
            ->assertNotFound();
    }

    public function test_destroy_removes_only_current_users_item(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Cellos', 'Orchestral', 'Arc');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Arc Cello',
            'published_at' => now()->subDay(),
        ]);

        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        WishlistItem::create([
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
        ]);

        $this->actingAs($otherUser)
            ->delete(route('storefront.wishlist.destroy', $instrument))
            ->assertNotFound();

        $this->actingAs($user)
            ->delete(route('storefront.wishlist.destroy', $instrument))
            ->assertRedirect();

        $this->assertDatabaseMissing('wishlist_items', [
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
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
            'description' => $overrides['description'] ?? 'Wishlist test instrument',
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
