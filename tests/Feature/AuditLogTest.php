<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Builder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_create_action(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin);

        $builder = Builder::create([
            'name' => 'Martin',
            'slug' => 'martin',
            'country' => 'USA',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'created',
            'auditable_type' => 'builder',
            'auditable_id' => $builder->id,
        ]);
    }

    public function test_it_logs_update_action_with_changed_fields_only(): void
    {
        $admin = User::factory()->create();
        $builder = Builder::factory()->create([
            'name' => 'Martin',
            'slug' => 'martin',
            'country' => 'USA',
        ]);

        $this->actingAs($admin);

        $builder->update([
            'country' => 'United States',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'updated',
            'auditable_type' => 'builder',
            'auditable_id' => $builder->id,
        ]);

        $auditLog = AuditLog::latest('id')->first();

        $this->assertSame('USA', $auditLog->old_values['country']);
        $this->assertSame('United States', $auditLog->new_values['country']);
        $this->assertArrayNotHasKey('name', $auditLog->new_values);
    }

    public function test_it_logs_delete_action(): void
    {
        $admin = User::factory()->create();
        $builder = Builder::factory()->create();

        $this->actingAs($admin);

        $builderId = $builder->id;
        $builder->delete();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'deleted',
            'auditable_type' => 'builder',
            'auditable_id' => $builderId,
        ]);
    }
}
