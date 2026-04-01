<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Wishlist</h2>
                <p class="text-sm text-gray-600">Instruments you saved for later.</p>
            </div>

            <a href="{{ route('storefront.instruments.index') }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Continue browsing
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

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

                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                    @class([
                                        'bg-emerald-100 text-emerald-700' =>
                                            $instrument?->stock_status === 'available',
                                        'bg-amber-100 text-amber-700' => $instrument?->stock_status === 'reserved',
                                        'bg-slate-200 text-slate-700' => $instrument?->stock_status === 'sold',
                                        'bg-slate-100 text-slate-500' => !in_array($instrument?->stock_status, [
                                            'available',
                                            'reserved',
                                            'sold',
                                        ]),
                                    ])">
                                        {{ ucfirst($instrument?->stock_status ?? 'unknown') }}
                                    </span>
                                </div>

                                <div class="space-y-2 text-sm text-slate-600">
                                    @if ($spec?->topWood?->name)
                                        <p><span class="font-medium text-slate-700">Top wood:</span> {{ $spec->topWood->name }}
                                        </p>
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

                                <div class="mt-5 flex items-center gap-3">
                                    <a href="{{ route('storefront.instruments.show', $instrument) }}"
                                        class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                        View details
                                    </a>

                                    <form action="{{ route('storefront.wishlist.destroy', $instrument) }}" method="POST"
                                        onsubmit="return confirm('Remove this instrument from your wishlist?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="inline-flex items-center rounded-md border border-rose-200 px-4 py-2 text-sm font-medium text-rose-700 hover:bg-rose-50">
                                            Remove
                                        </button>
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
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <h2 class="text-xl font-semibold text-slate-900">Your wishlist is empty</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Save instruments while browsing so you can return to them later.
                    </p>

                    <a href="{{ route('storefront.instruments.index') }}"
                        class="mt-6 inline-flex items-center rounded-md bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                        Browse instruments
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
