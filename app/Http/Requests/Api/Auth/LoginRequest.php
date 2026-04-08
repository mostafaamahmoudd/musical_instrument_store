<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class LoginRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'string', 'email'],
            'phone' => ['nullable', 'string', 'phone'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $this->email && ! $this->phone) {
                $validator->errors()->add('email', 'Either email or phone is required.');
                $validator->errors()->add('phone', 'Either email or phone is required.');
            }
        });
    }
}
