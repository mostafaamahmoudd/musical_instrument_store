<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogoutCustomerController extends Controller
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

        $user->currentAccessToken()?->delete();

        return $this->successResponse(
            'Customer logged out successfully.',
            null,
            Response::HTTP_OK
        );
    }
}
