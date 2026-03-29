<?php

namespace App\Http\Requests\Admin;

use App\Models\Instrument;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInstrumentRequest extends FormRequest
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
        $instrument = $this->route('instrument');

        return [
            'instrument_family_id' => ['required', 'exists:instrument_families,id'],
            'builder_id' => ['required', 'exists:builders,id'],
            'instrument_type_id' => ['required', 'exists:instrument_types,id'],
            'model' => ['nullable', 'string', 'max:255'],
            'num_strings' => ['nullable', 'integer', 'min:1'],
            'back_wood_id' => ['nullable', 'exists:wood,id'],
            'top_wood_id' => ['nullable', 'exists:wood,id'],
            'style' => ['nullable', 'string', 'max:255'],
            'finish' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'serial_number' => ['required', 'string', 'max:255', Rule::unique('instruments', 'serial_number')->ignore($instrument)],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('instruments', 'sku')->ignore($instrument)],
            'price' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', Rule::in(Instrument::conditionTypes())],
            'stock_status' => ['required', Rule::in(Instrument::stockStatus())],
            'year_made' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'quantity' => ['required', 'integer', 'min:1'],
            'featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_media' => ['nullable', 'array'],
            'delete_media.*' => ['integer', 'exists:media,id'],
        ];
    }
}
