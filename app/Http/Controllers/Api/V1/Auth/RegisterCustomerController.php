<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\CustomerResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterCustomerController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $validated = $request->validated() + [
            'is_active' => true,
            'type' => User::CUSTOMER_TYPE,
        ];

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->createdResponse('User registered successfully.', [
            'user' => CustomerResource::make($user),
            'access_token' => $token,
        ]);
    }
}
