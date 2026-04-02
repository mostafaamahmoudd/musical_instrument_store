<?php

namespace App\Http\Requests\Admin;

use App\Models\Reservation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
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
            'status' => ['required', Rule::in(Reservation::statuses())],
            'reserved_until' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $status = $this->input('status');
            $reservedUntil = $this->input('reserved_until');

            if ($status === Reservation::APPROVED && blank($reservedUntil)) {
                $validator->errors()->add('reserved_until', 'Reserved until is required when approving a reservation.');
            }
        });
    }
}
