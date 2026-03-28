<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Name')"/>
        <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                      :value="old('name', $builder->name ?? '')" required/>
        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
    </div>

    <div>
        <x-input-label for="slug" :value="__('Slug')"/>
        <x-text-input id="slug" name="slug" type="text" class="block mt-1 w-full"
                      :value="old('slug', $builder->slug ?? '')" required/>
        <x-input-error :messages="$errors->get('slug')" class="mt-2"/>
    </div>

    <div>
        <x-input-label for="country" :value="__('Country')"/>
        <x-text-input id="country" name="country" type="text" class="block mt-1 w-full"
                      :value="old('country', $builder->country ?? '')"/>
        <x-input-error :messages="$errors->get('country')" class="mt-2"/>
    </div>

    <div class="flex items-center gap-2">
        <input id="is_active" name="is_active" type="checkbox" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            @checked(old('is_active', $builder->is_active ?? true))>
        <label for="is_active" class="text-sm text-gray-700">Active</label>
    </div>

    <div class="flex items-center gap-3">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('admin.builders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
    </div>
</div>
