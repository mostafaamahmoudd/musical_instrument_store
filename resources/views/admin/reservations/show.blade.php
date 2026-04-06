<x-layouts.admin>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-slate-900">Reservation Details</h1>
            <a href="{{ route('admin.reservations.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to reservations
            </a>
        </div>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card title="Reservation Details">
                <dl class="mt-4 grid gap-5 sm:grid-cols-2">
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
            </x-ui.card>

            <x-ui.card title="Instrument">
                <div class="mt-2 flex flex-col gap-5 sm:flex-row">
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
                            Stock status: {{ str($reservation->instrument?->stock_status)->replace('_', ' ')->title() }}
                        </p>
                        <a
                            href="{{ route('storefront.instruments.show', $reservation->instrument) }}"
                            class="mt-3 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-800"
                            target="_blank" rel="noopener noreferrer"
                        >
                            View storefront page
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div>
            <x-ui.card title="Manage Reservation">
                <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST"
                      class="mt-4 space-y-5">
                    @csrf
                    @method('PATCH')

                    <x-ui.select
                        label="Status"
                        name="status"
                        :error="$errors->get('status')"
                    >
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $reservation->status) === $status)>
                                {{ str($status)->replace('_', ' ')->title() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.input
                        label="Reserved until"
                        name="reserved_until"
                        type="datetime-local"
                        :value="old('reserved_until', optional($reservation->reserved_until)->format('Y-m-d\TH:i'))"
                        :error="$errors->get('reserved_until')"
                    />

                    <x-ui.textarea
                        label="Admin / reservation notes"
                        name="notes"
                        rows="5"
                        :error="$errors->get('notes')"
                    >{{ old('notes', $reservation->notes) }}</x-ui.textarea>

                    <x-ui.button type="submit" class="w-full justify-center">
                        Update reservation
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
