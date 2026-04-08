<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Resources\Api\CustomerResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeCustomerController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (! $user->isCustomer()) {
            return $this->respondWithError(
                'This API is available for customer accounts only.',
                Response::HTTP_FORBIDDEN
            );
        }

        return $this->resourceResponse(CustomerResource::make($user));
    }
}
