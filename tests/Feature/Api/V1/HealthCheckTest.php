<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_endpoint_returns_ok_response(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'API is up and running.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'service',
                    'version',
                    'time',
                ],
                'meta',
            ]);
    }
}
