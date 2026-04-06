<div class="space-y-6">
    <x-ui.select
        label="Instrument Family"
        name="instrument_family_id"
        :error="$errors->get('instrument_family_id')"
        required
    >
        <option value="">Select a family</option>
        @foreach ($instrumentFamilies as $family)
            <option value="{{ $family->id }}" @selected(old('instrument_family_id', $instrumentType->instrument_family_id ?? '') == $family->id)>
                {{ $family->name }}
            </option>
        @endforeach
    </x-ui.select>

    <x-ui.input
        label="Name"
        name="name"
        :value="old('name', $instrumentType->name ?? '')"
        :error="$errors->get('name')"
        required
    />

    <x-ui.input
        label="Slug"
        name="slug"
        :value="old('slug', $instrumentType->slug ?? '')"
        :error="$errors->get('slug')"
        required
    />

    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button type="submit">{{ $submitLabel }}</x-ui.button>
        <x-ui.button href="{{ route('admin.instrument-types.index') }}" variant="secondary">Cancel</x-ui.button>
    </div>
</div>
