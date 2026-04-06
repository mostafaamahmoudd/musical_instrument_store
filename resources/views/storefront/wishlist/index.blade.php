<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">My Wishlist</h2>
                <p class="text-sm text-slate-500">Instruments you saved for later.</p>
            </div>

            <a href="{{ route('storefront.instruments.index') }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Continue browsing
            </a>
        </div>
    </x-slot>

    <x-ui.card>
        @if ($wishlistItems->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($wishlistItems as $wishlistItem)
                    @php
                        $instrument = $wishlistItem->instrument;
                        $spec = $instrument?->spec;
                        $imageUrl = $instrument?->getFirstMediaUrl('gallery', 'thumb');
                    @endphp

                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="aspect-[4/3] bg-slate-100">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}"
                                    alt="{{ $spec?->model ?: 'Instrument image' }}"
                                    class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-slate-400">
                                    No image
                                </div>
                            @endif
                        </div>

                        <div class="p-5">
                            <div class="mb-3 flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">
                                        {{ $spec?->builder?->name }} {{ $spec?->model }}
                                    </h2>
                                    <p class="text-sm text-slate-500">
                                        {{ $spec?->instrumentFamily?->name }} · {{ $spec?->instrumentType?->name }}
                                    </p>
                                </div>

                                <x-ui.badge :status="$instrument?->stock_status" />
                            </div>

                            <div class="space-y-2 text-sm text-slate-600">
                                @if ($spec?->topWood?->name)
                                    <p><span class="font-medium text-slate-700">Top wood:</span> {{ $spec->topWood->name }}</p>
                                @endif

                                @if ($spec?->backWood?->name)
                                    <p><span class="font-medium text-slate-700">Back wood:</span>
                                        {{ $spec->backWood->name }}</p>
                                @endif

                                @if ($instrument?->condition)
                                    <p><span class="font-medium text-slate-700">Condition:</span>
                                        {{ ucfirst($instrument->condition) }}</p>
                                @endif
                            </div>

                            <div class="mt-5 flex items-center justify-between">
                                <p class="text-lg font-bold text-slate-900">
                                    ${{ number_format((float) $instrument?->price, 2) }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    Saved {{ optional($wishlistItem->created_at)->diffForHumans() }}
                                </p>
                            </div>

                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <x-ui.button href="{{ route('storefront.instruments.show', $instrument) }}">
                                    View details
                                </x-ui.button>

                                <form action="{{ route('storefront.wishlist.destroy', $instrument) }}" method="POST"
                                    onsubmit="return confirm('Remove this instrument from your wishlist?')">
                                    @csrf
                                    @method('DELETE')

                                    <x-ui.button type="submit" variant="danger">
                                        Remove
                                    </x-ui.button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $wishlistItems->links() }}
            </div>
        @else
            <x-ui.empty-state
                title="Your wishlist is empty"
                description="Save instruments while browsing so you can return to them later."
            >
                <x-slot name="action">
                    <x-ui.button href="{{ route('storefront.instruments.index') }}">Browse instruments</x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        @endif
    </x-ui.card>
</x-app-layout>
