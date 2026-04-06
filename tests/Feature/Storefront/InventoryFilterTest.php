<?php

namespace Tests\Feature\Storefront;

use App\Models\Instrument;
use App\Models\InstrumentSpec;
use App\Models\Wood;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_filters_by_top_and_back_wood(): void
    {
        $spruce = Wood::factory()->create(['name' => 'Spruce', 'slug' => 'spruce']);
        $mahogany = Wood::factory()->create(['name' => 'Mahogany', 'slug' => 'mahogany']);

        $specMatch = InstrumentSpec::factory()->create([
            'top_wood_id' => $spruce->id,
            'back_wood_id' => $mahogany->id,
        ]);

        $specOther = InstrumentSpec::factory()->create([
            'top_wood_id' => $mahogany->id,
            'back_wood_id' => $spruce->id,
        ]);

        $matchInstrument = Instrument::factory()->published()->create([
            'instrument_spec_id' => $specMatch->id,
        ]);

        Instrument::factory()->published()->create([
            'instrument_spec_id' => $specOther->id,
        ]);

        $response = $this->get(route('storefront.instruments.index', [
            'top_wood' => $spruce->id,
            'back_wood' => $mahogany->id,
        ]));

        $response->assertOk()
            ->assertSeeText($matchInstrument->spec->builder->name)
            ->assertSeeText($matchInstrument->spec->model);
    }
}
