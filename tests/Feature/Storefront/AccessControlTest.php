<?php

namespace Tests\Feature\Storefront;

use App\Models\Instrument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_customer_storefront_pages(): void
    {
        $instrument = Instrument::factory()->published()->create([
            'stock_status' => Instrument::AVAILABLE,
        ]);

        $this->get(route('storefront.wishlist.index'))
            ->assertRedirect(route('login'));

        $this->get(route('storefront.inquiries.index'))
            ->assertRedirect(route('login'));

        $this->get(route('storefront.reservations.index'))
            ->assertRedirect(route('login'));

        $this->get(route('storefront.inquiries.create', $instrument))
            ->assertRedirect(route('login'));

        $this->get(route('storefront.reservations.create', $instrument))
            ->assertRedirect(route('login'));
    }

    public function test_customer_can_access_storefront_account_pages(): void
    {
        $instrument = Instrument::factory()->published()->create([
            'stock_status' => Instrument::AVAILABLE,
        ]);

        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $this->actingAs($customer)
            ->get(route('storefront.wishlist.index'))
            ->assertOk();

        $this->actingAs($customer)
            ->get(route('storefront.inquiries.index'))
            ->assertOk();

        $this->actingAs($customer)
            ->get(route('storefront.reservations.index'))
            ->assertOk();

        $this->actingAs($customer)
            ->get(route('storefront.inquiries.create', $instrument))
            ->assertOk();

        $this->actingAs($customer)
            ->get(route('storefront.reservations.create', $instrument))
            ->assertOk();
    }
}
