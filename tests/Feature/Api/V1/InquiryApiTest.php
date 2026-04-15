<?php

namespace Tests\Feature\Api\V1;

use App\Models\Inquiry;
use App\Models\Instrument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_inquiry_endpoints_require_authentication(): void
    {
        $instrument = Instrument::factory()->published()->create();
        $inquiry = Inquiry::factory()->create();

        $this->getJson('/api/v1/inquiries')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->getJson("/api/v1/inquiries/{$inquiry->id}")
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');

        $this->postJson("/api/v1/instruments/{$instrument->id}/inquiries", [])
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_authenticated_customer_can_list_only_their_inquiries(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $instrumentA = Instrument::factory()->published()->create([
            'serial_number' => 'SN-INQ-A',
        ]);

        $instrumentB = Instrument::factory()->published()->create([
            'serial_number' => 'SN-INQ-B',
        ]);

        Inquiry::factory()->create([
            'user_id' => $user->id,
            'instrument_id' => $instrumentA->id,
            'email' => 'owner@example.com',
            'status' => Inquiry::NEW,
        ]);

        Inquiry::factory()->create([
            'user_id' => $otherUser->id,
            'instrument_id' => $instrumentB->id,
            'email' => 'other@example.com',
            'status' => Inquiry::IN_PROGRESS,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/v1/inquiries');

        $response->assertOk()
            ->assertJsonPath('message', 'Inquiries retrieved successfully.')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.email', 'owner@example.com')
            ->assertJsonPath('data.0.status', Inquiry::NEW)
            ->assertJsonPath('data.0.instrument.serial_number', 'SN-INQ-A')
            ->assertJsonPath('meta.pagination.total', 1);
    }

    public function test_show_forbids_access_to_other_users_inquiry(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $instrument = Instrument::factory()->published()->create();

        $inquiry = Inquiry::factory()->create([
            'user_id' => $owner->id,
            'instrument_id' => $instrument->id,
        ]);

        $token = $otherUser->createToken('auth_token')->plainTextToken;

        $this->withToken($token)
            ->getJson("/api/v1/inquiries/{$inquiry->id}")
            ->assertForbidden();
    }

    public function test_store_creates_a_new_inquiry_for_visible_instrument(): void
    {
        $user = User::factory()->create();
        $instrument = Instrument::factory()->published()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withToken($token)->postJson(
            "/api/v1/instruments/{$instrument->id}/inquiries",
            [
                'name' => 'Test Customer',
                'email' => 'test@example.com',
                'phone' => '555-1234',
                'subject' => 'Need details',
                'message' => 'Can you share measurements?',
            ]
        );

        $response->assertOk()
            ->assertJsonPath('message', 'Your inquiry has been submitted successfully.');

        $this->assertDatabaseHas('inquiries', [
            'user_id' => $user->id,
            'instrument_id' => $instrument->id,
            'assigned_admin_id' => null,
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'status' => Inquiry::NEW,
        ]);
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

        $payload = [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'message' => 'Need details.',
        ];

        $this->withToken($token)
            ->postJson("/api/v1/instruments/{$hiddenInstrument->id}/inquiries", $payload)
            ->assertNotFound();

        $this->withToken($token)
            ->postJson("/api/v1/instruments/{$draftInstrument->id}/inquiries", $payload)
            ->assertNotFound();

        $this->withToken($token)
            ->postJson("/api/v1/instruments/{$futureInstrument->id}/inquiries", $payload)
            ->assertNotFound();
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $instrument = Instrument::factory()->published()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->withToken($token)
            ->postJson("/api/v1/instruments/{$instrument->id}/inquiries", [
                'name' => '',
                'email' => 'not-an-email',
                'message' => '',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'message']);
    }
}
