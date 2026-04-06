<div class="space-y-6">
    <x-ui.input
        label="Name"
        name="name"
        :value="old('name', $builder->name ?? '')"
        :error="$errors->get('name')"
        required
    />

    <x-ui.input
        label="Slug"
        name="slug"
        :value="old('slug', $builder->slug ?? '')"
        :error="$errors->get('slug')"
        required
    />

    <x-ui.input
        label="Country"
        name="country"
        :value="old('country', $builder->country ?? '')"
        :error="$errors->get('country')"
    />

    <div class="flex items-center gap-2">
        <input id="is_active" name="is_active" type="checkbox" value="1"
            class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-2 focus:ring-slate-200"
            @checked(old('is_active', $builder->is_active ?? true))>
        <label for="is_active" class="text-sm text-slate-700">Active</label>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button type="submit">{{ $submitLabel }}</x-ui.button>
        <x-ui.button href="{{ route('admin.builders.index') }}" variant="secondary">Cancel</x-ui.button>
    </div>
</div>
