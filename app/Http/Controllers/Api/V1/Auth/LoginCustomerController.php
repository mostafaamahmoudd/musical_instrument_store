<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\CustomerResource;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginCustomerController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $key = 'api-login:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
            ]);
        }

        RateLimiter::hit($key, 60);

        $user = User::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->isCustomer()) {
            return $this->respondWithError(
                'This API is available for customer accounts only.',
                Response::HTTP_FORBIDDEN
            );
        }

        if ($user->is_active === false) {
            return $this->respondWithError(
                'This account is inactive.',
                Response::HTTP_FORBIDDEN
            );
        }

        event(new Login('sanctum', $user, false));

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse('Login successful.', [
            'user' => CustomerResource::make($user),
            'access_token' => $token,
        ]);
    }
}
