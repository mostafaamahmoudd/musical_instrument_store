<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Request Reservation
                </h2>
                <p class="text-sm text-gray-600">
                    Submit a reservation request for this instrument.
                </p>
            </div>

            <a href="{{ route('storefront.instruments.show', $instrument) }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to instrument
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-1">
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
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
                            <p
                                class="mt-2 inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                Available
                            </p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h1 class="text-2xl font-bold text-slate-900">Request a reservation</h1>
                        <p class="mt-2 text-sm text-slate-600">
                            The store team will review your request and approve or reject it.
                        </p>

                        <div class="mt-5 rounded-lg bg-slate-50 p-4 text-sm text-slate-700">
                            <p><span class="font-medium text-slate-900">Customer:</span> {{ $user->name }}</p>
                            <p class="mt-1"><span class="font-medium text-slate-900">Email:</span> {{ $user->email }}</p>
                            @if ($user->phone)
                                <p class="mt-1"><span class="font-medium text-slate-900">Phone:</span> {{ $user->phone }}
                                </p>
                            @endif
                        </div>

                        <form action="{{ route('storefront.reservations.store', $instrument) }}" method="POST"
                            class="mt-6 space-y-5">
                            @csrf

                            <div>
                                <label for="notes" class="mb-1 block text-sm font-medium text-slate-700">Notes
                                    (optional)</label>
                                <textarea id="notes" name="notes" rows="6"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-0"
                                    placeholder="Add any details for the store team, such as preferred contact time or reservation questions.">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit"
                                    class="inline-flex items-center rounded-md bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                                    Submit reservation request
                                </button>

                                <a href="{{ route('storefront.instruments.show', $instrument) }}"
                                    class="inline-flex items-center rounded-md border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
