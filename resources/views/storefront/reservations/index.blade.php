<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">My Reservations</h2>
            <p class="text-sm text-slate-500">Track your reservation requests and their status.</p>
        </div>
    </x-slot>

    <x-ui.card padding="sm">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <x-ui.select label="Status" name="status" class="min-w-[200px]">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected($selectedStatus === $status)>
                        {{ str($status)->replace('_', ' ')->title() }}
                    </option>
                @endforeach
            </x-ui.select>

            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button type="submit">Filter</x-ui.button>
                <x-ui.button href="{{ route('storefront.reservations.index') }}" variant="secondary">Reset</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card padding="none">
        @if ($reservations->count())
            <x-ui.table>
                <x-slot name="head">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3">Instrument</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Reserved Until</th>
                        <th class="px-4 py-3">Created</th>
                        <th class="px-4 py-3">Notes</th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                <a href="{{ route('storefront.instruments.show', $reservation->instrument) }}"
                                    class="font-medium text-slate-900 hover:text-slate-700">
                                    {{ $reservation->instrument?->spec?->builder?->name }}
                                    {{ $reservation->instrument?->spec?->model }}
                                </a>
                            </td>
                            <td class="px-4 py-4">
                                <x-ui.badge :status="$reservation->status" />
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-500">
                                {{ $reservation->reserved_until?->format('Y-m-d H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-500">
                                {{ $reservation->created_at?->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-500">
                                {{ $reservation->notes ?: '—' }}
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-ui.table>

            <div class="p-6">
                {{ $reservations->links() }}
            </div>
        @else
            <div class="p-6">
                <x-ui.empty-state
                    title="No reservations yet"
                    description="You have not submitted any reservation requests yet."
                />
            </div>
        @endif
    </x-ui.card>
</x-app-layout>
