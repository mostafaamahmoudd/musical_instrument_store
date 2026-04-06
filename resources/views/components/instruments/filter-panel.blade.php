@props([
    'families' => [],
    'builders' => [],
    'types' => [],
    'woods' => [],
    'conditions' => [],
    'stockStatuses' => [],
])

<x-ui.card title="Search inventory" description="Filter by builder, woods, and pricing details.">
    <form method="GET" action="{{ route('storefront.instruments.index') }}" class="space-y-6">
        <x-ui.input
            label="Search"
            name="q"
            :value="request('q')"
            placeholder="Search by model, style, finish, or description"
        />

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <x-ui.select label="Family" name="family">
                <option value="">All families</option>
                @foreach ($families as $family)
                    <option value="{{ $family->id }}" @selected((string) request('family') === (string) $family->id)>
                        {{ $family->name }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select label="Builder" name="builder">
                <option value="">All builders</option>
                @foreach ($builders as $builder)
                    <option value="{{ $builder->id }}" @selected((string) request('builder') === (string) $builder->id)>
                        {{ $builder->name }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select label="Type" name="type">
                <option value="">All types</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" @selected((string) request('type') === (string) $type->id)>
                        {{ $type->name }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select label="Top Wood" name="top_wood">
                <option value="">All top woods</option>
                @foreach ($woods as $wood)
                    <option value="{{ $wood->id }}" @selected((string) request('top_wood') === (string) $wood->id)>
                        {{ $wood->name }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select label="Back Wood" name="back_wood">
                <option value="">All back woods</option>
                @foreach ($woods as $wood)
                    <option value="{{ $wood->id }}" @selected((string) request('back_wood') === (string) $wood->id)>
                        {{ $wood->name }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select label="Condition" name="condition">
                <option value="">All conditions</option>
                @foreach ($conditions as $condition)
                    <option value="{{ $condition }}" @selected(request('condition') === $condition)>
                        {{ ucfirst($condition) }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select label="Stock Status" name="stock">
                <option value="">All stock statuses</option>
                @foreach ($stockStatuses as $status)
                    @if ($status !== \App\Models\Instrument::HIDDEN)
                        <option value="{{ $status }}" @selected(request('stock') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endif
                @endforeach
            </x-ui.select>

            <x-ui.input label="Min Price" name="price_min" type="number" step="0.01" min="0" :value="request('price_min')" />

            <x-ui.input label="Max Price" name="price_max" type="number" step="0.01" min="0" :value="request('price_max')" />

            <x-ui.select label="Sort" name="sort">
                <option value="">Newest first</option>
                <option value="price_low_high" @selected(request('sort') === 'price_low_high')>Price: Low to High</option>
                <option value="price_high_low" @selected(request('sort') === 'price_high_low')>Price: High to Low</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
            </x-ui.select>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <x-ui.button type="submit">Search Inventory</x-ui.button>
            <a href="{{ route('storefront.instruments.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                Reset Filters
            </a>
        </div>
    </form>
</x-ui.card>
