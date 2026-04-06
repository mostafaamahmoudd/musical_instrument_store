<div class="space-y-6">
    <x-ui.input
        label="Name"
        name="name"
        :value="old('name', $instrumentFamily->name ?? '')"
        :error="$errors->get('name')"
        required
    />

    <x-ui.input
        label="Slug"
        name="slug"
        :value="old('slug', $instrumentFamily->slug ?? '')"
        :error="$errors->get('slug')"
        required
    />

    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button type="submit">{{ $submitLabel }}</x-ui.button>
        <x-ui.button href="{{ route('admin.instrument-families.index') }}" variant="secondary">Cancel</x-ui.button>
    </div>
</div>
