<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">
                    {{ $currentFamily ? $currentFamily->name . ' Inventory' : 'Inventory Search' }}
                </h2>
                <p class="text-sm text-slate-500">
                    Search published instruments by specification and inventory filters.
                </p>
            </div>

            <a href="{{ route('home') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to Home
            </a>
        </div>
    </x-slot>

    <x-instruments.filter-panel
        :families="$families"
        :builders="$builders"
        :types="$types"
        :woods="$woods"
        :conditions="$conditions"
        :stock-statuses="$stockStatuses"
    />

    <x-ui.card>
        @php
            $activeFilters = collect([
                'q' => request('q'),
                'family' => request('family'),
                'builder' => request('builder'),
                'type' => request('type'),
                'top_wood' => request('top_wood'),
                'back_wood' => request('back_wood'),
                'condition' => request('condition'),
                'stock' => request('stock'),
                'price_min' => request('price_min'),
                'price_max' => request('price_max'),
                'sort' => request('sort'),
            ])->filter(fn($value) => $value !== null && $value !== '');
        @endphp

        @if ($activeFilters->isNotEmpty())
            <div class="mb-4 text-sm text-slate-600">
                Active filters: {{ $activeFilters->count() }}
            </div>
        @endif

        @if ($instruments->count())
            <div class="mb-6 text-sm text-slate-600">
                Showing {{ $instruments->firstItem() }}-{{ $instruments->lastItem() }} of
                {{ $instruments->total() }} instruments.
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($instruments as $instrument)
                    <x-instruments.card :instrument="$instrument" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $instruments->links() }}
            </div>
        @else
            <x-ui.empty-state
                title="No instruments found"
                description="Try changing your search term or adjusting the specification filters."
            />
        @endif
    </x-ui.card>
</x-app-layout>
