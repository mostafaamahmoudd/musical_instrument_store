<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Builder;
use App\Models\Inquiry;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentSpec;
use App\Models\InstrumentType;
use App\Models\PriceHistory;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Wood;
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

    public function test_admin_dashboard_displays_metrics_recent_activity_and_sidebar_links(): void
    {
        $admin = User::factory()->create([
            'type' => User::ADMIN_TYPE,
            'name' => 'Admin User',
        ]);

        $family = InstrumentFamily::create([
            'name' => 'Guitars',
            'slug' => 'guitars',
        ]);

        $type = InstrumentType::create([
            'instrument_family_id' => $family->id,
            'name' => 'Acoustic',
            'slug' => 'acoustic',
        ]);

        $builder = Builder::create([
            'name' => 'Martin',
            'slug' => 'martin',
            'country' => 'USA',
            'is_active' => true,
        ]);

        $wood = Wood::create([
            'name' => 'Mahogany',
            'slug' => 'mahogany',
        ]);

        $specOne = InstrumentSpec::create([
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => 'D-18',
            'back_wood_id' => $wood->id,
            'top_wood_id' => $wood->id,
        ]);

        $specTwo = InstrumentSpec::create([
            'instrument_family_id' => $family->id,
            'builder_id' => $builder->id,
            'instrument_type_id' => $type->id,
            'model' => '000-15M',
            'back_wood_id' => $wood->id,
            'top_wood_id' => $wood->id,
        ]);

        $publishedAvailableInstrument = Instrument::create([
            'instrument_spec_id' => $specOne->id,
            'serial_number' => 'SN-100',
            'sku' => 'SKU-100',
            'price' => 2500.00,
            'condition' => Instrument::NEW_CONDITION,
            'stock_status' => Instrument::AVAILABLE,
            'year_made' => '2024-01-01',
            'quantity' => 1,
            'featured' => false,
            'published_at' => now(),
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        $hiddenInstrument = Instrument::create([
            'instrument_spec_id' => $specTwo->id,
            'serial_number' => 'SN-200',
            'sku' => 'SKU-200',
            'price' => 1800.00,
            'condition' => Instrument::USED_CONDITION,
            'stock_status' => Instrument::HIDDEN,
            'year_made' => '2020-01-01',
            'quantity' => 1,
            'featured' => false,
            'published_at' => null,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);

        Inquiry::create([
            'user_id' => $admin->id,
            'instrument_id' => $publishedAvailableInstrument->id,
            'name' => 'Interested Buyer',
            'email' => 'buyer@example.com',
            'status' => Inquiry::NEW,
            'message' => 'Is this still available?',
        ]);

        Reservation::create([
            'user_id' => $admin->id,
            'instrument_id' => $publishedAvailableInstrument->id,
            'status' => Reservation::PENDING,
            'notes' => 'Please hold until Friday.',
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'action' => AuditLog::UPDATED,
            'auditable_type' => 'instrument',
            'auditable_id' => $publishedAvailableInstrument->id,
            'old_values' => ['price' => '2400.00'],
            'new_values' => ['price' => '2500.00'],
        ]);

        PriceHistory::create([
            'instrument_id' => $publishedAvailableInstrument->id,
            'changed_by' => $admin->id,
            'old_price' => 2400.00,
            'new_price' => 2500.00,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Admin panel');
        $response->assertSee('Dashboard');
        $response->assertSee('Total instruments');
        $response->assertSee('2');
        $response->assertSee('Published instruments');
        $response->assertSee('1');
        $response->assertSee('Available instruments');
        $response->assertSee('Pending inquiries');
        $response->assertSee('Pending reservations');
        $response->assertSee('Latest audit activity');
        $response->assertSee('Updated');
        $response->assertSee('Instrument');
        $response->assertSee('Latest price changes');
        $response->assertSee('SN-100');
        $response->assertSee('Admin User');
        $response->assertSee(route('admin.instruments.index'), false);
        $response->assertSee(route('admin.builders.index'), false);
        $response->assertDontSee($hiddenInstrument->sku);
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

    public function test_customer_dashboard_uses_customer_sidebar_links(): void
    {
        $customer = User::factory()->create([
            'type' => User::CUSTOMER_TYPE,
        ]);

        $response = $this->actingAs($customer)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Customer account');
        $response->assertSee('Browse Inventory');
        $response->assertSee(route('storefront.instruments.index'), false);
        $response->assertSee(route('storefront.wishlist.index'), false);
        $response->assertSee(route('profile.edit'), false);
        $response->assertDontSee(route('admin.instruments.index'), false);
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
