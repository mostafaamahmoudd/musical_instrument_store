<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Reservations</h1>
            <p class="text-sm text-slate-500">Review and manage customer reservation requests.</p>
        </div>
    </x-slot>

    <x-ui.card padding="sm">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <x-ui.select label="Status" name="status" class="min-w-[200px]">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected($statusFilter === $status)>
                        {{ str($status)->replace('_', ' ')->title() }}
                    </option>
                @endforeach
            </x-ui.select>

            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button type="submit">Filter</x-ui.button>
                <x-ui.button href="{{ route('admin.reservations.index') }}" variant="secondary">Reset</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card padding="none">
        @if ($reservations->count())
            <x-ui.table>
                <x-slot name="head">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Instrument</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Reserved Until</th>
                        <th class="px-4 py-3">Created</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-slate-900">{{ $reservation->user?->name }}</div>
                                <div class="text-sm text-slate-500">{{ $reservation->user?->email }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                {{ $reservation->instrument?->spec?->builder?->name }}
                                {{ $reservation->instrument?->spec?->model }}
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
                            <td class="px-4 py-4 text-right">
                                <a
                                    href="{{ route('admin.reservations.show', $reservation) }}"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                >
                                    View
                                </a>
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
                    title="No reservations found"
                    description="Reservation requests will appear here as customers submit them."
                />
            </div>
        @endif
    </x-ui.card>
</x-layouts.admin>
