<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Reservation Details</h1>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.reservations.index') }}" class="text-sm text-slate-600 hover:text-slate-900">
                ← Back to reservations
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h1 class="text-2xl font-bold text-slate-900">Reservation Details</h1>

                    <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Customer name</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $reservation->user?->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-500">Customer email</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $reservation->user?->email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-500">Current status</dt>
                            <dd class="mt-1 text-sm text-slate-900">
                                {{ str($reservation->status)->replace('_', ' ')->title() }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-500">Reserved until</dt>
                            <dd class="mt-1 text-sm text-slate-900">
                                {{ $reservation->reserved_until?->format('Y-m-d H:i') ?? '—' }}
                            </dd>
                        </div>

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-slate-500">Customer notes</dt>
                            <dd class="mt-1 rounded-lg bg-slate-50 p-4 text-sm leading-6 text-slate-800">
                                {{ $reservation->notes ?: 'No notes provided.' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Instrument</h2>

                    <div class="mt-4 flex gap-5">
                        <div class="h-28 w-28 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                            @php
                                $instrumentImage = $reservation->instrument?->getFirstMediaUrl('gallery', 'preview');
                            @endphp
                            @if ($instrumentImage)
                                <img
                                    src="{{ $instrumentImage }}"
                                    alt="{{ trim(($reservation->instrument?->spec?->builder?->name ?? '') . ' ' . ($reservation->instrument?->spec?->model ?? 'Instrument')) }}"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <div class="flex h-full items-center justify-center text-xs text-slate-400">
                                    No image
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-slate-900">
                                {{ $reservation->instrument?->spec?->builder?->name }}
                                {{ $reservation->instrument?->spec?->model }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $reservation->instrument?->spec?->instrumentFamily?->name }}
                                ·
                                {{ $reservation->instrument?->spec?->instrumentType?->name }}
                            </p>
                            <p class="mt-3 text-sm text-slate-700">
                                Price: ${{ number_format((float) $reservation->instrument?->price, 2) }}
                            </p>
                            <p class="mt-1 text-sm text-slate-700">
                                Stock
                                status: {{ str($reservation->instrument?->stock_status)->replace('_', ' ')->title() }}
                            </p>
                            <a
                                href="{{ route('storefront.instruments.show', $reservation->instrument) }}"
                                class="mt-3 inline-flex text-sm font-medium text-slate-900 hover:text-slate-700"
                                target="_blank" rel="noopener noreferrer"
                            >
                                View storefront page
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Manage Reservation</h2>

                    <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST"
                          class="mt-6 space-y-5">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                            >
                                @foreach ($statuses as $status)
                                    <option
                                        value="{{ $status }}" @selected(old('status', $reservation->status) === $status)>
                                        {{ str($status)->replace('_', ' ')->title() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reserved_until" class="mb-1 block text-sm font-medium text-slate-700">Reserved
                                until</label>
                            <input
                                id="reserved_until"
                                name="reserved_until"
                                type="datetime-local"
                                value="{{ old('reserved_until', optional($reservation->reserved_until)->format('Y-m-d\TH:i')) }}"
                                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                            >
                            @error('reserved_until')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="mb-1 block text-sm font-medium text-slate-700">Admin / reservation
                                notes</label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="5"
                                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                            >{{ old('notes', $reservation->notes) }}</textarea>
                            @error('notes')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-md bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800"
                        >
                            Update reservation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
