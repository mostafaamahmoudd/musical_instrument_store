@php
    $instrument = $instrument ?? null;
    $spec = $instrument?->spec;
@endphp

<div class="space-y-8">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Specification Details</h3>
        <p class="mt-1 text-sm text-gray-600">
            These fields describe the instrument itself.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="instrument_family_id" :value="__('Instrument Family')" />
            <select id="instrument_family_id" name="instrument_family_id"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required>
                <option value="">Select a family</option>
                @foreach ($instrumentFamilies as $family)
                    <option value="{{ $family->id }}" @selected(old('instrument_family_id', $spec->instrument_family_id ?? '') == $family->id)>
                        {{ $family->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('instrument_family_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="builder_id" :value="__('Builder')" />
            <select id="builder_id" name="builder_id"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required>
                <option value="">Select a builder</option>
                @foreach ($builders as $builder)
                    <option value="{{ $builder->id }}" @selected(old('builder_id', $spec->builder_id ?? '') == $builder->id)>
                        {{ $builder->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('builder_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="instrument_type_id" :value="__('Instrument Type')" />
            <select id="instrument_type_id" name="instrument_type_id"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required>
                <option value="">Select a type</option>
                @foreach ($instrumentTypes as $type)
                    <option value="{{ $type->id }}" @selected(old('instrument_type_id', $spec->instrument_type_id ?? '') == $type->id)>
                        {{ $type->name }} ({{ $type->instrumentFamily?->name }})
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('instrument_type_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="model" :value="__('Model')" />
            <x-text-input id="model" name="model" type="text" class="block mt-1 w-full" :value="old('model', $spec->model ?? '')" />
            <x-input-error :messages="$errors->get('model')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="num_strings" :value="__('Number of Strings')" />
            <x-text-input id="num_strings" name="num_strings" type="number" min="1" class="block mt-1 w-full"
                :value="old('num_strings', $spec->num_strings ?? '')" />
            <x-input-error :messages="$errors->get('num_strings')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="back_wood_id" :value="__('Back Wood')" />
            <select id="back_wood_id" name="back_wood_id"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select back wood</option>
                @foreach ($woods as $wood)
                    <option value="{{ $wood->id }}" @selected(old('back_wood_id', $spec->back_wood_id ?? '') == $wood->id)>
                        {{ $wood->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('back_wood_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="top_wood_id" :value="__('Top Wood')" />
            <select id="top_wood_id" name="top_wood_id"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select top wood</option>
                @foreach ($woods as $wood)
                    <option value="{{ $wood->id }}" @selected(old('top_wood_id', $spec->top_wood_id ?? '') == $wood->id)>
                        {{ $wood->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('top_wood_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="style" :value="__('Style')" />
            <x-text-input id="style" name="style" type="text" class="block mt-1 w-full" :value="old('style', $spec->style ?? '')" />
            <x-input-error :messages="$errors->get('style')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="finish" :value="__('Finish')" />
            <x-text-input id="finish" name="finish" type="text" class="block mt-1 w-full" :value="old('finish', $spec->finish ?? '')" />
            <x-input-error :messages="$errors->get('finish')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" rows="4"
            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $spec->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="border-t pt-8">
        <h3 class="text-lg font-semibold text-gray-900">Inventory Details</h3>
        <p class="mt-1 text-sm text-gray-600">
            These fields describe the actual store item.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="serial_number" :value="__('Serial Number')" />
            <x-text-input id="serial_number" name="serial_number" type="text" class="block mt-1 w-full"
                :value="old('serial_number', $instrument?->serial_number ?? '')" required />
            <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="sku" :value="__('SKU')" />
            <x-text-input id="sku" name="sku" type="text" class="block mt-1 w-full" :value="old('sku', $instrument?->sku ?? '')" />
            <x-input-error :messages="$errors->get('sku')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="price" :value="__('Price')" />
            <x-text-input id="price" name="price" type="number" step="0.01" min="0"
                class="block mt-1 w-full" :value="old('price', $instrument?->price ?? '')" required />
            <x-input-error :messages="$errors->get('price')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="condition" :value="__('Condition')" />
            <select id="condition" name="condition"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required>
                <option value="">Select condition</option>
                @foreach ($conditions as $condition)
                    <option value="{{ $condition }}" @selected(old('condition', $instrument?->condition ?? '') === $condition)>
                        {{ ucfirst($condition) }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('condition')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="stock_status" :value="__('Stock Status')" />
            <select id="stock_status" name="stock_status"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required>
                <option value="">Select status</option>
                @foreach ($stockStatuses as $status)
                    <option value="{{ $status }}" @selected(old('stock_status', $instrument?->stock_status ?? '') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('stock_status')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="year_made" :value="__('Year Made')" />
            <x-text-input id="year_made" name="year_made" type="number" min="1800" class="block mt-1 w-full"
                :value="old('year_made', $instrument?->year_made?->format('Y') ?? '')" />
            <x-input-error :messages="$errors->get('year_made')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="quantity" :value="__('Quantity')" />
            <x-text-input id="quantity" name="quantity" type="number" min="1" class="block mt-1 w-full"
                :value="old('quantity', $instrument?->quantity ?? 1)" required />
            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="published_at" :value="__('Published At')" />
            <x-text-input id="published_at" name="published_at" type="datetime-local" class="block mt-1 w-full"
                :value="old(
                    'published_at',
                    $instrument?->published_at
                        ? $instrument->published_at->format('Y-m-d\TH:i')
                        : '',
                )" />
            <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
        </div>

        <div class="flex items-center gap-2 pt-8">
            <input id="featured" name="featured" type="checkbox" value="1"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                @checked(old('featured', $instrument?->featured ?? false))>
            <label for="featured" class="text-sm text-gray-700">Featured</label>
        </div>
    </div>

    <div class="border-t pt-8">
        <h3 class="text-lg font-semibold text-gray-900">Images</h3>
        <p class="mt-1 text-sm text-gray-600">
            Upload one or more images for this instrument.
        </p>
    </div>

    <div>
        <x-input-label for="images" :value="__('Upload Images')" />
        <input id="images" name="images[]" type="file" multiple accept=".jpg,.jpeg,.png,.webp"
            class="block mt-1 w-full text-sm text-gray-700 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <x-input-error :messages="$errors->get('images')" class="mt-2" />
        <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
    </div>

    @if (isset($instrument) && $instrument->getMedia('gallery')->count())
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Existing Images</h4>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($instrument->getMedia('gallery') as $media)
                    <div class="border rounded-lg p-3 bg-gray-50">
                        <img src="{{ $media->getUrl('thumb') }}" alt="Instrument image"
                            class="w-full h-32 object-cover rounded-md">

                        <label class="mt-3 flex items-center gap-2 text-sm text-red-700">
                            <input type="checkbox" name="delete_media[]" value="{{ $media->id }}">
                            Delete
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="flex items-center gap-3 pt-4">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>

        <a href="{{ route('admin.instruments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
            Cancel
        </a>
    </div>
</div>
