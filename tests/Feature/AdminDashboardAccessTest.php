<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $response = $this->actingAs($customer)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
    }

    public function test_customer_can_access_customer_dashboard(): void
    {
        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $response = $this->actingAs($customer)->get('/dashboard');

        $response->assertOk();
        $response->assertViewIs('customer.dashboard');
    }

    public function test_admin_cannot_access_customer_dashboard(): void
    {
        $admin = User::factory()->create([
            'type' => User::ADMIN_TYPE,
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(403);
    }
}
