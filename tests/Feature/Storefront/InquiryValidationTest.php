<?php

namespace Tests\Feature\Storefront;

use App\Models\Instrument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_inquiry_requires_name_email_and_message(): void
    {
        $instrument = Instrument::factory()->published()->create([
            'stock_status' => Instrument::AVAILABLE,
        ]);

        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $response = $this->actingAs($customer)
            ->from(route('storefront.inquiries.create', $instrument))
            ->post(route('storefront.inquiries.store', $instrument), [
                'name' => '',
                'email' => '',
                'message' => '',
            ]);

        $response->assertRedirect(route('storefront.inquiries.create', $instrument));
        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
