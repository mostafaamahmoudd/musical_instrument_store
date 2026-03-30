<div class="bg-white shadow-sm sm:rounded-lg">
    <div class="p-6">
        <form method="GET" action="{{ route('storefront.instruments.index') }}"
              class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4">
            <div>
                <label for="family" class="block text-sm font-medium text-gray-700">Family</label>
                <select id="family" name="family"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All families</option>
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}" @selected(request('family') == $family->id)>
                            {{ $family->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="builder" class="block text-sm font-medium text-gray-700">Builder</label>
                <select id="builder" name="builder"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All builders</option>
                    @foreach ($builders as $builder)
                        <option value="{{ $builder->id }}" @selected(request('builder') == $builder->id)>
                            {{ $builder->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="condition" class="block text-sm font-medium text-gray-700">Condition</label>
                <select id="condition" name="condition"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All conditions</option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition }}" @selected(request('condition') === $condition)>
                            {{ ucfirst($condition) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Sort</label>
                <select id="sort" name="sort"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Newest first</option>
                    <option value="price_low_high" @selected(request('sort') === 'price_low_high')>Price: Low to High
                    </option>
                    <option value="price_high_low" @selected(request('sort') === 'price_high_low')>Price: High to Low
                    </option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
                </select>
            </div>

            <div>
                <label for="lowPrice" class="block text-sm font-medium text-gray-700">Min Price</label>
                <input
                    id="lowPrice"
                    name="lowPrice"
                    type="number"
                    min="0"
                    step="0.01"
                    value="{{ request('lowPrice') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label for="highPrice" class="block text-sm font-medium text-gray-700">Max Price</label>
                <input
                    id="highPrice"
                    name="highPrice"
                    type="number"
                    min="0"
                    step="0.01"
                    value="{{ request('highPrice') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div class="md:col-span-3 xl:col-span-6 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-black">
                    Apply Filters
                </button>

                <a href="{{ route('storefront.instruments.index') }}"
                   class="text-sm text-gray-600 hover:text-gray-900">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>
