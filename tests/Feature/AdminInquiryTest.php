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

class AdminInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_inquiries_index(): void
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

        $customer = User::factory()->create();

        Inquiry::create([
            'user_id' => $customer->id,
            'instrument_id' => $instrument->id,
            'assigned_admin_id' => null,
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'phone' => '555-8888',
            'subject' => 'Need info',
            'message' => 'Hello!',
            'status' => Inquiry::NEW,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.inquiries.index'))
            ->assertOk()
            ->assertViewIs('admin.inquiries.index')
            ->assertSeeText('Arc Cello');
    }

    public function test_admin_can_update_inquiry_status_and_assignment(): void
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

        $assignee = User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);

        $customer = User::factory()->create();

        $inquiry = Inquiry::create([
            'user_id' => $customer->id,
            'instrument_id' => $instrument->id,
            'assigned_admin_id' => null,
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'phone' => null,
            'subject' => 'Availability',
            'message' => 'Is it in stock?',
            'status' => Inquiry::NEW,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.inquiries.update', $inquiry), [
                'status' => Inquiry::CLOSED,
                'assigned_admin_id' => $assignee->id,
            ])
            ->assertRedirect(route('admin.inquiries.show', $inquiry));

        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'status' => Inquiry::CLOSED,
            'assigned_admin_id' => $assignee->id,
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
            'description' => $overrides['description'] ?? 'Inquiry admin test instrument',
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
