<div class="bg-white shadow-sm sm:rounded-lg">
    <div class="p-6">
        <form method="GET" action="{{ route('storefront.instruments.index') }}" class="space-y-6">
            <div>
                <label for="q" class="block text-sm font-medium text-gray-700">Search</label>
                <input id="q" name="q" type="text" value="{{ request('q') }}"
                    placeholder="Search by model, style, finish, or description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <div>
                    <label for="family" class="block text-sm font-medium text-gray-700">Family</label>
                    <select id="family" name="family"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All families</option>
                        @foreach ($families as $family)
                            <option value="{{ $family->id }}" @selected((string) request('family') === (string) $family->id)>
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
                            <option value="{{ $builder->id }}" @selected((string) request('builder') === (string) $builder->id)>
                                {{ $builder->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select id="type" name="type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All types</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @selected((string) request('type') === (string) $type->id)>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="top_wood" class="block text-sm font-medium text-gray-700">Top Wood</label>
                    <select id="top_wood" name="top_wood"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All top woods</option>
                        @foreach ($woods as $wood)
                            <option value="{{ $wood->id }}" @selected((string) request('top_wood') === (string) $wood->id)>
                                {{ $wood->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="back_wood" class="block text-sm font-medium text-gray-700">Back Wood</label>
                    <select id="back_wood" name="back_wood"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All back woods</option>
                        @foreach ($woods as $wood)
                            <option value="{{ $wood->id }}" @selected((string) request('back_wood') === (string) $wood->id)>
                                {{ $wood->name }}
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
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stock Status</label>
                    <select id="stock" name="stock"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All stock statuses</option>
                        @foreach ($stockStatuses as $status)
                            @if ($status !== \App\Models\Instrument::HIDDEN)
                                <option value="{{ $status }}" @selected(request('stock') === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="price_min" class="block text-sm font-medium text-gray-700">Min Price</label>
                    <input id="price_min" name="price_min" type="number" step="0.01" min="0"
                        value="{{ request('price_min') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="price_max" class="block text-sm font-medium text-gray-700">Max Price</label>
                    <input id="price_max" name="price_max" type="number" step="0.01" min="0"
                        value="{{ request('price_max') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort</label>
                    <select id="sort" name="sort"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Newest first</option>
                        <option value="price_low_high" @selected(request('sort') === 'price_low_high')>Price: Low to High</option>
                        <option value="price_high_low" @selected(request('sort') === 'price_high_low')>Price: High to Low</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-black">
                    Search Inventory
                </button>

                <a href="{{ route('storefront.instruments.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-900">
                    Reset Filters
                </a>
            </div>
        </form>
    </div>
</div>
