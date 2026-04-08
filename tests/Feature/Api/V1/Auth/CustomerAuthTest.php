<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_and_receive_token(): void
    {
        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+15555550123',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertCreated()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'access_token',
                ],
                'errors',
                'meta',
            ])
            ->assertJsonPath('data.user.email', 'jane@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'type' => User::CUSTOMER_TYPE,
            'is_active' => true,
        ]);

        $this->assertNotEmpty($response->json('data.access_token'));
    }

    public function test_customer_can_login_and_receive_token(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'type' => User::CUSTOMER_TYPE,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'customer@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.user.email', 'customer@example.com');

        $this->assertNotEmpty($response->json('data.access_token'));
    }

    public function test_invalid_credentials_return_validation_error(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed.',
            ])
            ->assertJsonStructure([
                'errors' => ['email'],
            ]);
    }

    public function test_non_customer_cannot_login_to_customer_api(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'type' => User::ADMIN_TYPE,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertForbidden()
            ->assertJson([
                'success' => false,
                'message' => 'This API is available for customer accounts only.',
            ]);
    }

    public function test_inactive_customer_cannot_login(): void
    {
        $inactive = User::factory()->create([
            'email' => 'inactive@example.com',
            'type' => User::CUSTOMER_TYPE,
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $inactive->email,
            'password' => 'password',
        ]);

        $response->assertForbidden()
            ->assertJson([
                'success' => false,
                'message' => 'This account is inactive.',
            ]);
    }

    public function test_me_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertUnauthorized()
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_logout_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertUnauthorized()
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_customer_can_access_me_with_bearer_token(): void
    {
        $user = User::factory()->create([
            'email' => 'me@example.com',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/v1/auth/me');

        $response->assertOk()
            ->assertJsonPath('data.email', 'me@example.com');
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $this->assertSame(1, $user->tokens()->count());

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/logout');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Customer logged out successfully.',
            ]);

        $user->refresh();
        $this->assertSame(0, $user->tokens()->count());
    }

    public function test_login_is_rate_limited_after_too_many_attempts(): void
    {
        $key = 'api-login:127.0.0.1';
        RateLimiter::clear($key);

        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => 'nobody@example.com',
                'password' => 'password',
            ])->assertStatus(422);
        }

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nobody@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $this->assertStringContainsString(
            'Too many login attempts',
            $response->json('errors.email.0')
        );
    }
}
