@php
    $instrument = $instrument ?? null;
    $spec = $instrument?->spec;
@endphp

<div class="space-y-8">
    <div>
        <h3 class="text-lg font-semibold text-slate-900">Specification Details</h3>
        <p class="mt-1 text-sm text-slate-600">
            These fields describe the instrument itself.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <x-ui.select
            label="Instrument Family"
            name="instrument_family_id"
            :error="$errors->get('instrument_family_id')"
            required
        >
            <option value="">Select a family</option>
            @foreach ($instrumentFamilies as $family)
                <option value="{{ $family->id }}" @selected(old('instrument_family_id', $spec->instrument_family_id ?? '') == $family->id)>
                    {{ $family->name }}
                </option>
            @endforeach
        </x-ui.select>

        <x-ui.select
            label="Builder"
            name="builder_id"
            :error="$errors->get('builder_id')"
            required
        >
            <option value="">Select a builder</option>
            @foreach ($builders as $builder)
                <option value="{{ $builder->id }}" @selected(old('builder_id', $spec->builder_id ?? '') == $builder->id)>
                    {{ $builder->name }}
                </option>
            @endforeach
        </x-ui.select>

        <x-ui.select
            label="Instrument Type"
            name="instrument_type_id"
            :error="$errors->get('instrument_type_id')"
            required
        >
            <option value="">Select a type</option>
            @foreach ($instrumentTypes as $type)
                <option value="{{ $type->id }}" @selected(old('instrument_type_id', $spec->instrument_type_id ?? '') == $type->id)>
                    {{ $type->name }} ({{ $type->instrumentFamily?->name }})
                </option>
            @endforeach
        </x-ui.select>

        <x-ui.input
            label="Model"
            name="model"
            :value="old('model', $spec->model ?? '')"
            :error="$errors->get('model')"
        />

        <x-ui.input
            label="Number of Strings"
            name="num_strings"
            type="number"
            min="1"
            :value="old('num_strings', $spec->num_strings ?? '')"
            :error="$errors->get('num_strings')"
        />

        <x-ui.select
            label="Back Wood"
            name="back_wood_id"
            :error="$errors->get('back_wood_id')"
        >
            <option value="">Select back wood</option>
            @foreach ($woods as $wood)
                <option value="{{ $wood->id }}" @selected(old('back_wood_id', $spec->back_wood_id ?? '') == $wood->id)>
                    {{ $wood->name }}
                </option>
            @endforeach
        </x-ui.select>

        <x-ui.select
            label="Top Wood"
            name="top_wood_id"
            :error="$errors->get('top_wood_id')"
        >
            <option value="">Select top wood</option>
            @foreach ($woods as $wood)
                <option value="{{ $wood->id }}" @selected(old('top_wood_id', $spec->top_wood_id ?? '') == $wood->id)>
                    {{ $wood->name }}
                </option>
            @endforeach
        </x-ui.select>

        <x-ui.input
            label="Style"
            name="style"
            :value="old('style', $spec->style ?? '')"
            :error="$errors->get('style')"
        />

        <x-ui.input
            label="Finish"
            name="finish"
            :value="old('finish', $spec->finish ?? '')"
            :error="$errors->get('finish')"
        />
    </div>

    <x-ui.textarea
        label="Description"
        name="description"
        rows="4"
        :error="$errors->get('description')"
    >{{ old('description', $spec->description ?? '') }}</x-ui.textarea>

    <div class="border-t border-slate-200 pt-8">
        <h3 class="text-lg font-semibold text-slate-900">Inventory Details</h3>
        <p class="mt-1 text-sm text-slate-600">
            These fields describe the actual store item.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <x-ui.input
            label="Serial Number"
            name="serial_number"
            :value="old('serial_number', $instrument?->serial_number ?? '')"
            :error="$errors->get('serial_number')"
            required
        />

        <x-ui.input
            label="SKU"
            name="sku"
            :value="old('sku', $instrument?->sku ?? '')"
            :error="$errors->get('sku')"
        />

        <x-ui.input
            label="Price"
            name="price"
            type="number"
            step="0.01"
            min="0"
            :value="old('price', $instrument?->price ?? '')"
            :error="$errors->get('price')"
            required
        />

        <x-ui.select
            label="Condition"
            name="condition"
            :error="$errors->get('condition')"
            required
        >
            <option value="">Select condition</option>
            @foreach ($conditions as $condition)
                <option value="{{ $condition }}" @selected(old('condition', $instrument?->condition ?? '') === $condition)>
                    {{ ucfirst($condition) }}
                </option>
            @endforeach
        </x-ui.select>

        <x-ui.select
            label="Stock Status"
            name="stock_status"
            :error="$errors->get('stock_status')"
            required
        >
            <option value="">Select status</option>
            @foreach ($stockStatuses as $status)
                <option value="{{ $status }}" @selected(old('stock_status', $instrument?->stock_status ?? '') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </x-ui.select>

        <x-ui.input
            label="Year Made"
            name="year_made"
            type="number"
            min="1800"
            :value="old('year_made', $instrument?->year_made?->format('Y') ?? '')"
            :error="$errors->get('year_made')"
        />

        <x-ui.input
            label="Quantity"
            name="quantity"
            type="number"
            min="1"
            :value="old('quantity', $instrument?->quantity ?? 1)"
            :error="$errors->get('quantity')"
            required
        />

        <x-ui.input
            label="Published At"
            name="published_at"
            type="datetime-local"
            :value="old(
                'published_at',
                $instrument?->published_at
                    ? $instrument->published_at->format('Y-m-d\TH:i')
                    : '',
            )"
            :error="$errors->get('published_at')"
        />

        <div class="flex items-center gap-2 pt-2">
            <input id="featured" name="featured" type="checkbox" value="1"
                class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-2 focus:ring-slate-200"
                @checked(old('featured', $instrument?->featured ?? false))>
            <label for="featured" class="text-sm text-slate-700">Featured</label>
        </div>
    </div>

    <div class="border-t border-slate-200 pt-8">
        <h3 class="text-lg font-semibold text-slate-900">Images</h3>
        <p class="mt-1 text-sm text-slate-600">
            Upload one or more images for this instrument.
        </p>
    </div>

    <x-ui.input
        label="Upload Images"
        id="images"
        name="images[]"
        type="file"
        multiple
        accept=".jpg,.jpeg,.png,.webp"
        :error="$errors->get('images')"
        class="file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-slate-800"
    />

    @if (isset($instrument) && $instrument->getMedia('gallery')->count())
        <div class="mt-6">
            <h4 class="mb-3 text-sm font-medium text-slate-900">Existing Images</h4>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($instrument->getMedia('gallery') as $media)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <img src="{{ $media->getUrl('thumb') }}" alt="Instrument image"
                            class="h-32 w-full rounded-md object-cover">

                        <label class="mt-3 flex items-center gap-2 text-sm text-rose-700">
                            <input type="checkbox" name="delete_media[]" value="{{ $media->id }}">
                            Delete
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-3 pt-4">
        <x-ui.button type="submit">{{ $submitLabel }}</x-ui.button>
        <x-ui.button href="{{ route('admin.instruments.index') }}" variant="secondary">Cancel</x-ui.button>
    </div>
</div>
