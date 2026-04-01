<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $instrument->spec?->builder?->name ?? 'Instrument' }} {{ $instrument->spec?->model ?? '' }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ $instrument->spec?->instrumentType?->name ?? 'Instrument' }}
                </p>
            </div>

            <a href="{{ route('storefront.instruments.index', ['family' => $instrument->spec?->instrument_family_id]) }}"
               class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to Inventory
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div>
                        <div class="rounded-xl overflow-hidden bg-gray-100">
                            @if ($instrument->getFirstMediaUrl('gallery', 'preview'))
                                <img
                                    src="{{ $instrument->getFirstMediaUrl('gallery', 'preview') }}"
                                    alt="{{ trim(($instrument->spec?->builder?->name ?? '') . ' ' . ($instrument->spec?->model ?? 'Instrument')) }}"
                                    class="w-full h-[420px] object-cover"
                                >
                            @else
                                <div class="w-full h-[420px] flex items-center justify-center text-gray-500">
                                    No image available
                                </div>
                            @endif
                        </div>

                        @if ($instrument->getMedia('gallery')->count() > 1)
                            <div class="mt-4 grid grid-cols-4 gap-3">
                                @foreach ($instrument->getMedia('gallery') as $media)
                                    <div class="rounded-lg overflow-hidden bg-gray-100">
                                        <img
                                            src="{{ $media->getUrl('thumb') }}"
                                            alt="Instrument gallery image"
                                            class="w-full h-24 object-cover"
                                        >
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div>
                            <p class="text-sm uppercase tracking-wide text-gray-500">
                                {{ $instrument->spec?->instrumentFamily?->name ?? 'Instrument Family' }}
                            </p>

                            <h1 class="mt-1 text-3xl font-bold text-gray-900">
                                {{ $instrument->spec?->builder?->name ?? 'Unknown Builder' }}
                                {{ $instrument->spec?->model ?? '' }}
                            </h1>

                            <p class="mt-2 text-gray-600">
                                {{ $instrument->spec?->instrumentType?->name ?? 'Type not set' }}
                            </p>

                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ number_format((float) $instrument->price, 2) }}
                                </p>

                                @include('storefront.inventory.partials.wishlist-button', ['instrument' => $instrument])
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-gray-500">Condition</p>
                                <p class="mt-1 font-medium text-gray-900 capitalize">{{ $instrument->condition }}</p>
                            </div>

                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-gray-500">Stock Status</p>
                                <p class="mt-1 font-medium text-gray-900 capitalize">{{ $instrument->stock_status }}</p>
                            </div>

                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-gray-500">Year Made</p>
                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $instrument->year_made ? $instrument->year_made->format('Y') : 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-gray-500">Number of Strings</p>
                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $instrument->spec?->num_strings ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-gray-500">Back Wood</p>
                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $instrument->spec?->backWood?->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-gray-500">Top Wood</p>
                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $instrument->spec?->topWood?->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-gray-500">Style</p>
                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $instrument->spec?->style ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-gray-500">Finish</p>
                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $instrument->spec?->finish ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Description</h3>
                            <p class="mt-2 text-gray-700 leading-7">
                                {{ $instrument->spec?->description ?: 'No description available for this instrument yet.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($related->count())
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900">Related Instruments</h3>
                        <p class="mt-2 text-gray-600">
                            More instruments from the same family.
                        </p>

                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                            @foreach ($related as $relatedInstrument)
                                @include('storefront.instruments._card', ['instrument' => $relatedInstrument])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
