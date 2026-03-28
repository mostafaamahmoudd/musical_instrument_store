<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstrumentTypeRequest extends FormRequest
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
        $instrumentType = $this->route('instrumentType');

        return [
            'instrument_family_id' => 'required|exists:instrument_families,id',
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('instrument_types', 'slug')
                ->ignore($instrumentType)],
        ];
    }
}
