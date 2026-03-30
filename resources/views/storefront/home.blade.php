<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rick's Musical Instruments</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900">Browse by Instrument Family</h1>
                    <p class="mt-2 text-gray-600">
                        Explore the inventory by family, then drill down into individual instruments.
                    </p>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse ($families as $family)
                            <a href="{{ route('storefront.instruments.index', ['family' => $family->id]) }}"
                               class="block rounded-xl border border-gray-200 bg-gray-50 p-6 hover:border-indigo-400 hover:bg-white hover:shadow transition">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $family->name }}</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    View instruments in the {{ strtolower($family->name) }} family.
                                </p>
                                <div class="mt-4 text-sm font-medium text-indigo-600">
                                    Browse family →
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-600">No instrument families found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Featured Instruments</h2>
                            <p class="mt-2 text-gray-600">
                                A quick look at selected instruments currently visible in the storefront.
                            </p>
                        </div>

                        <a href="{{ route('storefront.instruments.index') }}"
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            View all inventory
                        </a>
                    </div>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                        @forelse ($instruments as $instrument)
                            @include('storefront.instruments._card', ['instrument' => $instrument])
                        @empty
                            <p class="text-sm text-gray-600">No featured instruments available yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
