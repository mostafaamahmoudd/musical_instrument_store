<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiFormRequest extends FormRequest
{
    use ApiResponse;

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->respondValidationErrors($validator->errors()->toArray())
        );
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            $this->respondForbidden('This action is forbidden.')
        );
    }
}
