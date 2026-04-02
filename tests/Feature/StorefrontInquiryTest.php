<?php

namespace Tests\Feature;

use App\Models\Builder;
use App\Models\Inquiry;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StorefrontInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_only_current_users_inquiries(): void
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

        Inquiry::create([
            'user_id' => $user->id,
            'instrument_id' => $instrumentA->id,
            'assigned_admin_id' => null,
            'name' => 'Customer A',
            'email' => 'a@example.com',
            'phone' => '555-1111',
            'subject' => 'Question A',
            'message' => 'Need details.',
            'status' => Inquiry::NEW,
        ]);

        Inquiry::create([
            'user_id' => $otherUser->id,
            'instrument_id' => $instrumentB->id,
            'assigned_admin_id' => null,
            'name' => 'Customer B',
            'email' => 'b@example.com',
            'phone' => '555-2222',
            'subject' => 'Question B',
            'message' => 'Other user inquiry.',
            'status' => Inquiry::NEW,
        ]);

        $response = $this->actingAs($user)
            ->get(route('storefront.inquiries.index'));

        $response->assertOk()
            ->assertViewIs('storefront.inquiries.index')
            ->assertSeeText('Atlas A')
            ->assertDontSeeText('Atlas B');
    }

    public function test_show_forbids_other_users_inquiry(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Violins', 'Classical', 'Bowline');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Bowline 1',
            'published_at' => now()->subDay(),
        ]);

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $inquiry = Inquiry::create([
            'user_id' => $owner->id,
            'instrument_id' => $instrument->id,
            'assigned_admin_id' => null,
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'phone' => null,
            'subject' => null,
            'message' => 'Owner inquiry.',
            'status' => Inquiry::NEW,
        ]);

        $this->actingAs($otherUser)
            ->get(route('storefront.inquiries.show', $inquiry))
            ->assertForbidden();
    }

    public function test_store_creates_inquiry_and_redirects(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Mandolins', 'Flatback', 'Quiet Build');

        $instrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Quiet 1',
            'published_at' => now()->subDay(),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('storefront.inquiries.store', $instrument), [
                'name' => 'Test Customer',
                'email' => 'test@example.com',
                'phone' => '555-4444',
                'subject' => 'Checking availability',
                'message' => 'Is this still available?',
            ]);

        $response->assertRedirect(route('storefront.instruments.show', $instrument));

        $this->assertDatabaseHas('inquiries', [
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
            'assigned_admin_id' => null,
            'email' => 'test@example.com',
            'status' => Inquiry::NEW,
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
            'description' => $overrides['description'] ?? 'Inquiry test instrument',
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
