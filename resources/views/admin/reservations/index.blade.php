<x-app-layout>
    <x-slot name="header">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Reservations</h1>
                <p class="mt-2 text-sm text-slate-600">Review and manage customer reservation requests.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        @if (session('success'))
            <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET"
              class="mb-6 flex flex-wrap items-end gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                <select id="status" name="status" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected($statusFilter === $status)>
                            {{ str($status)->replace('_', ' ')->title() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800"
            >
                Filter
            </button>

            <a
                href="{{ route('admin.reservations.index') }}"
                class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                Reset
            </a>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Customer
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Instrument
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Reserved Until
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Created
                        </th>
                        <th class="px-4 py-3"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($reservations as $reservation)
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
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                    @class([
                                        'bg-blue-100 text-blue-700' => $reservation->status === 'pending',
                                        'bg-emerald-100 text-emerald-700' => $reservation->status === 'approved',
                                        'bg-rose-100 text-rose-700' => $reservation->status === 'rejected',
                                        'bg-amber-100 text-amber-700' => $reservation->status === 'expired',
                                    ])">
                                    {{ str($reservation->status)->replace('_', ' ')->title() }}
                                </span>
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
                                    class="text-sm font-medium text-slate-900 hover:text-slate-700"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                No reservations found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    </div>
</x-app-layout>
