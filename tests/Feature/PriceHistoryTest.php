<?php

namespace Tests\Feature;

use App\Models\Instrument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_price_history_when_price_changes(): void
    {
        $admin = User::factory()->create();
        $instrument = Instrument::factory()->create([
            'price' => 1000.00,
        ]);

        $this->actingAs($admin);

        $instrument->update([
            'price' => 1200.00,
        ]);

        $this->assertDatabaseHas('price_histories', [
            'instrument_id' => $instrument->id,
            'old_price' => 1000.00,
            'new_price' => 1200.00,
            'changed_by' => $admin->id,
        ]);
    }

    public function test_it_does_not_create_price_history_when_price_does_not_change(): void
    {
        $instrument = Instrument::factory()->create([
            'price' => 1000.00,
            'quantity' => 1,
        ]);

        $instrument->update([
            'quantity' => 2,
        ]);

        $this->assertDatabaseCount('price_histories', 0);
    }
}
