<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name', $instrumentFamily->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="slug" :value="__('Slug')" />
        <x-text-input id="slug" name="slug" type="text" class="block mt-1 w-full" :value="old('slug', $instrumentFamily->slug ?? '')" required />
        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('admin.instrument-families.index') }}"
            class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</div>
