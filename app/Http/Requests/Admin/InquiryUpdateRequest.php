<?php

namespace App\Http\Requests\Admin;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InquiryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Inquiry::statuses())],
            'assigned_admin_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('type', User::ADMIN_TYPE),
            ],
        ];
    }
}
