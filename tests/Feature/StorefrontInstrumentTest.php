<?php

namespace Tests\Feature;

use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class StorefrontInstrumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_families_and_only_visible_featured_instruments(): void
    {
        [$guitars, $acoustic, $builder] = $this->createCatalog('Guitars', 'Acoustic', 'North Strings');
        [$violins, $violinType, $violinBuilder] = $this->createCatalog('Violins', 'Classical', 'Bow Makers');

        $featuredVisible = $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builder,
            'model' => 'Featured Visible',
            'featured' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builder,
            'model' => 'Featured Hidden',
            'featured' => true,
            'stock_status' => Instrument::HIDDEN,
            'published_at' => now()->subDay(),
        ]);

        $this->createInstrument([
            'family' => $violins,
            'type' => $violinType,
            'builder' => $violinBuilder,
            'model' => 'Featured Draft',
            'featured' => true,
        ]);

        $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builder,
            'model' => 'Visible Not Featured',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertViewIs('storefront.home')
            ->assertSeeText('Guitars')
            ->assertSeeText('Violins')
            ->assertSeeText($featuredVisible->spec->builder->name)
            ->assertSeeText('Featured Visible')
            ->assertDontSeeText('Featured Hidden')
            ->assertDontSeeText('Featured Draft')
            ->assertDontSeeText('Visible Not Featured');
    }

    public function test_inventory_page_filters_normalizes_price_bounds_and_sorts_results(): void
    {
        [$guitars, $acoustic, $builderA] = $this->createCatalog('Guitars', 'Acoustic', 'Builder A');
        [$violins, $violinType, $builderB] = $this->createCatalog('Violins', 'Classical', 'Builder B');

        $matchLow = $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builderA,
            'model' => 'Match Low',
            'condition' => Instrument::USED_CONDITION,
            'price' => 950,
            'published_at' => now()->subDays(2),
        ]);

        $matchHigh = $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builderA,
            'model' => 'Match High',
            'condition' => Instrument::USED_CONDITION,
            'price' => 1200,
            'published_at' => now()->subDay(),
        ]);

        $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builderA,
            'model' => 'Wrong Condition',
            'condition' => Instrument::NEW_CONDITION,
            'price' => 1100,
            'published_at' => now()->subDay(),
        ]);

        $this->createInstrument([
            'family' => $violins,
            'type' => $violinType,
            'builder' => $builderB,
            'model' => 'Wrong Family',
            'condition' => Instrument::USED_CONDITION,
            'price' => 1000,
            'published_at' => now()->subDay(),
        ]);

        $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builderA,
            'model' => 'Out Of Range',
            'condition' => Instrument::USED_CONDITION,
            'price' => 1501,
            'published_at' => now()->subDay(),
        ]);

        $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builderA,
            'model' => 'Hidden Match',
            'condition' => Instrument::USED_CONDITION,
            'price' => 1000,
            'stock_status' => Instrument::HIDDEN,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('storefront.instruments.index', [
            'family' => $guitars->id,
            'builder' => $builderA->id,
            'condition' => Instrument::USED_CONDITION,
            'lowPrice' => 1500,
            'highPrice' => 900,
            'sort' => 'price_low_high',
        ]));

        $response->assertOk()
            ->assertViewIs('storefront.instruments.index')
            ->assertViewHas('currentFamily', fn (?InstrumentFamily $family) => $family?->is($guitars))
            ->assertViewHas('instruments', function (LengthAwarePaginator $instruments) use ($matchLow, $matchHigh) {
                return $instruments->getCollection()->pluck('id')->values()->all() === [
                    $matchLow->id,
                    $matchHigh->id,
                ];
            })
            ->assertSeeText('Match Low')
            ->assertSeeText('Match High')
            ->assertDontSeeText('Wrong Condition')
            ->assertDontSeeText('Wrong Family')
            ->assertDontSeeText('Out Of Range')
            ->assertDontSeeText('Hidden Match');
    }

    public function test_show_page_returns_404_for_hidden_or_unpublished_instruments(): void
    {
        [$family, $type, $builder] = $this->createCatalog('Mandolins', 'Flatback', 'Quiet Build');

        $hiddenInstrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Hidden Detail',
            'stock_status' => Instrument::HIDDEN,
            'published_at' => now()->subDay(),
        ]);

        $draftInstrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Draft Detail',
            'published_at' => null,
        ]);

        $futureInstrument = $this->createInstrument([
            'family' => $family,
            'type' => $type,
            'builder' => $builder,
            'model' => 'Future Detail',
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('storefront.instruments.show', $hiddenInstrument))->assertNotFound();
        $this->get(route('storefront.instruments.show', $draftInstrument))->assertNotFound();
        $this->get(route('storefront.instruments.show', $futureInstrument))->assertNotFound();
    }

    public function test_show_page_displays_only_visible_related_instruments_from_same_family(): void
    {
        [$guitars, $acoustic, $builder] = $this->createCatalog('Guitars', 'Acoustic', 'Stage Craft');
        [$violins, $violinType, $violinBuilder] = $this->createCatalog('Violins', 'Classical', 'String House');

        $instrument = $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builder,
            'model' => 'Main Detail',
            'published_at' => now()->subDays(3),
        ]);

        $relatedVisible = $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builder,
            'model' => 'Related Visible',
            'published_at' => now()->subDay(),
        ]);

        $this->createInstrument([
            'family' => $guitars,
            'type' => $acoustic,
            'builder' => $builder,
            'model' => 'Related Hidden',
            'stock_status' => Instrument::HIDDEN,
            'published_at' => now()->subDay(),
        ]);

        $this->createInstrument([
            'family' => $violins,
            'type' => $violinType,
            'builder' => $violinBuilder,
            'model' => 'Other Family Visible',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('storefront.instruments.show', $instrument));

        $response->assertOk()
            ->assertViewIs('storefront.instruments.show')
            ->assertSeeText($relatedVisible->spec->builder->name)
            ->assertSeeText('Related Visible')
            ->assertDontSeeText('Related Hidden')
            ->assertDontSeeText('Other Family Visible');
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
            'description' => $overrides['description'] ?? 'Storefront test instrument',
        ]);

        return Instrument::create([
            'instrument_spec_id' => $spec->id,
            'serial_number' => $overrides['serial_number'] ?? 'SN-' . fake()->unique()->numerify('####'),
            'sku' => $overrides['sku'] ?? 'SKU-' . fake()->unique()->numerify('####'),
            'price' => $overrides['price'] ?? 999.99,
            'condition' => $overrides['condition'] ?? Instrument::NEW_CONDITION,
            'stock_status' => $overrides['stock_status'] ?? Instrument::AVAILABLE,
            'year_made' => $overrides['year_made'] ?? '2020-01-01',
            'quantity' => $overrides['quantity'] ?? 1,
            'featured' => $overrides['featured'] ?? false,
            'published_at' => $overrides['published_at'] ?? now()->subHour(),
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);
    }
}
