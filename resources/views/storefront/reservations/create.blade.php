<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Request Reservation</h2>
                <p class="text-sm text-slate-500">Submit a reservation request for this instrument.</p>
            </div>

            <a href="{{ route('storefront.instruments.show', $instrument) }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to instrument
            </a>
        </div>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <x-ui.card padding="none">
                <div class="aspect-[4/3] bg-slate-100">
                    @php
                        $instrumentImage = $instrument->getFirstMediaUrl('gallery', 'preview');
                    @endphp
                    @if ($instrumentImage)
                        <img src="{{ $instrumentImage }}"
                            alt="{{ trim(($instrument->spec?->builder?->name ?? '') . ' ' . ($instrument->spec?->model ?? 'Instrument')) }}"
                            class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center text-sm text-slate-400">
                            No image
                        </div>
                    @endif
                </div>

                <div class="p-5">
                    <h2 class="text-lg font-semibold text-slate-900">
                        {{ $instrument->spec?->builder?->name }} {{ $instrument->spec?->model }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $instrument->spec?->instrumentFamily?->name }}
                        · {{ $instrument->spec?->instrumentType?->name }}
                    </p>
                    <p class="mt-4 text-xl font-bold text-slate-900">
                        ${{ number_format((float) $instrument->price, 2) }}
                    </p>
                    <div class="mt-3">
                        <x-ui.badge status="available" />
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="lg:col-span-2">
            <x-ui.card>
                <h1 class="text-2xl font-bold text-slate-900">Request a reservation</h1>
                <p class="mt-2 text-sm text-slate-600">
                    The store team will review your request and approve or reject it.
                </p>

                <div class="mt-5 rounded-lg bg-slate-50 p-4 text-sm text-slate-700">
                    <p><span class="font-medium text-slate-900">Customer:</span> {{ $user->name }}</p>
                    <p class="mt-1"><span class="font-medium text-slate-900">Email:</span> {{ $user->email }}</p>
                    @if ($user->phone)
                        <p class="mt-1"><span class="font-medium text-slate-900">Phone:</span> {{ $user->phone }}</p>
                    @endif
                </div>

                <form action="{{ route('storefront.reservations.store', $instrument) }}" method="POST"
                    class="mt-6 space-y-5">
                    @csrf

                    <x-ui.textarea
                        label="Notes (optional)"
                        name="notes"
                        rows="6"
                        placeholder="Add any details for the store team, such as preferred contact time or reservation questions."
                        :error="$errors->get('notes')"
                    >{{ old('notes') }}</x-ui.textarea>

                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.button type="submit">Submit reservation request</x-ui.button>
                        <x-ui.button href="{{ route('storefront.instruments.show', $instrument) }}" variant="secondary">
                            Cancel
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
