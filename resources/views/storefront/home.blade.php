<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Rick's Musical Instruments</h2>
            <p class="text-sm text-slate-500">Browse the latest handcrafted inventory and featured builds.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <h1 class="text-3xl font-bold text-slate-900">Browse by Instrument Family</h1>
        <p class="mt-2 text-slate-600">
            Explore the inventory by family, then drill down into individual instruments.
        </p>

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($families as $family)
                <a href="{{ route('storefront.instruments.index', ['family' => $family->id]) }}"
                   class="group block rounded-xl border border-slate-200 bg-slate-50 p-6 transition hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-white hover:shadow">
                    <h3 class="text-lg font-semibold text-slate-900">{{ $family->name }}</h3>
                    <p class="mt-2 text-sm text-slate-600">
                        View instruments in the {{ strtolower($family->name) }} family.
                    </p>
                    <div class="mt-4 text-sm font-medium text-indigo-600 group-hover:text-indigo-700">
                        Browse family →
                    </div>
                </a>
            @empty
                <x-ui.empty-state
                    title="No instrument families found"
                    description="Check back soon once the catalog is populated."
                />
            @endforelse
        </div>
    </x-ui.card>

    <x-ui.card>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Featured Instruments</h2>
                <p class="mt-2 text-slate-600">
                    A quick look at selected instruments currently visible in the storefront.
                </p>
            </div>

            <a href="{{ route('storefront.instruments.index') }}"
               class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                View all inventory
            </a>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($instruments as $instrument)
                <x-instruments.card :instrument="$instrument" />
            @empty
                <x-ui.empty-state
                    title="No featured instruments available"
                    description="Feature a few instruments in the admin panel to highlight them here."
                />
            @endforelse
        </div>
    </x-ui.card>
</x-app-layout>
