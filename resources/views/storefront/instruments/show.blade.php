<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">
                    {{ $instrument->spec?->builder?->name ?? 'Instrument' }} {{ $instrument->spec?->model ?? '' }}
                </h2>
                <p class="text-sm text-slate-500">
                    {{ $instrument->spec?->instrumentType?->name ?? 'Instrument' }}
                </p>
            </div>

            <a href="{{ route('storefront.instruments.index', ['family' => $instrument->spec?->instrument_family_id]) }}"
               class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to Inventory
            </a>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
            <x-instruments.image-gallery :instrument="$instrument" />

            <div class="space-y-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-slate-500">
                        {{ $instrument->spec?->instrumentFamily?->name ?? 'Instrument Family' }}
                    </p>

                    <h1 class="mt-1 text-3xl font-bold text-slate-900">
                        {{ $instrument->spec?->builder?->name ?? 'Unknown Builder' }}
                        {{ $instrument->spec?->model ?? '' }}
                    </h1>

                    <p class="mt-2 text-slate-600">
                        {{ $instrument->spec?->instrumentType?->name ?? 'Type not set' }}
                    </p>

                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <p class="text-2xl font-bold text-slate-900">
                            ${{ number_format((float) $instrument->price, 2) }}
                        </p>

                        <x-ui.badge :status="$instrument->stock_status" />
                        <x-ui.badge variant="neutral">{{ ucfirst($instrument->condition) }}</x-ui.badge>
                    </div>

                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        @include('storefront.inventory.partials.wishlist-button', ['instrument' => $instrument])

                        @auth
                            <x-ui.button href="{{ route('storefront.inquiries.create', $instrument) }}">
                                Send inquiry
                            </x-ui.button>

                            @if ($instrument->stock_status === \App\Models\Instrument::AVAILABLE)
                                <x-ui.button href="{{ route('storefront.reservations.create', $instrument) }}" variant="secondary">
                                    Request reservation
                                </x-ui.button>
                            @endif
                        @else
                            <x-ui.button href="{{ route('login') }}" variant="secondary">
                                Log in to inquire
                            </x-ui.button>
                        @endauth
                    </div>
                </div>

                <x-instruments.spec-table :instrument="$instrument" />

                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Description</h3>
                    <p class="mt-2 text-slate-700 leading-7">
                        {{ $instrument->spec?->description ?: 'No description available for this instrument yet.' }}
                    </p>
                </div>
            </div>
        </div>
    </x-ui.card>

    @if ($related->count())
        <x-ui.card>
            <h3 class="text-2xl font-bold text-slate-900">Related Instruments</h3>
            <p class="mt-2 text-slate-600">
                More instruments from the same family.
            </p>

            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($related as $relatedInstrument)
                    <x-instruments.card :instrument="$relatedInstrument" />
                @endforeach
            </div>
        </x-ui.card>
    @endif
</x-app-layout>
