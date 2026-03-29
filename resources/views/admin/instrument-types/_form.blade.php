<div class="space-y-6">
    <div>
        <x-input-label for="instrument_family_id" :value="__('Instrument Family')" />
        <select id="instrument_family_id" name="instrument_family_id"
            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required>
            <option value="">Select a family</option>
            @foreach ($instrumentFamilies as $family)
                <option value="{{ $family->id }}" @selected(old('instrument_family_id', $instrumentType->instrument_family_id ?? '') == $family->id)>
                    {{ $family->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('instrument_family_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name', $instrumentType->name ?? '')"
            required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="slug" :value="__('Slug')" />
        <x-text-input id="slug" name="slug" type="text" class="block mt-1 w-full" :value="old('slug', $instrumentType->slug ?? '')"
            required />
        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('admin.instrument-types.index') }}"
            class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</div>
