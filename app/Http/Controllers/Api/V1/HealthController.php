<?php

namespace App\Http\Controllers\Api\V1;

class HealthController extends Controller
{
    public function __invoke()
    {
        return $this->successResponse('API is up and running.', [
            'service' => 'musical-instrument-store-api',
            'version' => 'v1',
            'time' => now()->toIso8601String(),
        ]);
    }
}
