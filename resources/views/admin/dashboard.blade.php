<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Admin Dashboard</p>
            <h2 class="mt-1 text-2xl font-semibold text-slate-900">Overview</h2>
        </div>
    </x-slot>

    <div class="space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Overview of inventory, customer requests, and recent admin activity.
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.instruments.index') }}"
                   class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                    Manage Instruments
                </a>

                <a href="{{ route('admin.inquiries.index') }}"
                   class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    View Inquiries
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total instruments</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($metrics['total_instruments']) }}</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Published instruments</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($metrics['published_instruments']) }}</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Available instruments</p>
                <p class="mt-3 text-3xl font-bold text-emerald-600">{{ number_format($metrics['available_instruments']) }}</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Pending inquiries</p>
                <p class="mt-3 text-3xl font-bold text-amber-600">{{ number_format($metrics['pending_inquiries']) }}</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Pending reservations</p>
                <p class="mt-3 text-3xl font-bold text-sky-600">{{ number_format($metrics['pending_reservations']) }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Latest audit activity</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Recent create, update, and delete actions from admin workflows.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                User
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Action
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Target
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                When
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($latestAuditLogs as $log)
                            <tr>
                                <td class="px-5 py-4 text-sm text-slate-700">
                                    {{ $log->user?->name ?? 'System' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                        @class([
                                            'bg-emerald-100 text-emerald-700' => $log->action === 'created',
                                            'bg-amber-100 text-amber-700' => $log->action === 'updated',
                                            'bg-rose-100 text-rose-700' => $log->action === 'deleted',
                                            'bg-slate-100 text-slate-700' => ! in_array($log->action, ['created', 'updated', 'deleted']),
                                        ])">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-700">
                                    <div class="font-medium">{{ str($log->auditable_type)->headline() }}</div>
                                    <div class="text-xs text-slate-500">#{{ $log->auditable_id }}</div>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-500">
                                    {{ $log->created_at?->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500">
                                    No audit activity recorded yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Latest price changes</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Most recent instrument price updates tracked by the system.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Instrument
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Old
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                New
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Changed by
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($latestPriceChanges as $change)
                            <tr>
                                <td class="px-5 py-4 text-sm text-slate-700">
                                    <div class="font-medium">
                                        {{ $change->instrument?->serial_number ?? 'Deleted instrument' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $change->created_at?->diffForHumans() }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-500">
                                    ${{ number_format((float) $change->old_price, 2) }}
                                </td>

                                <td class="px-5 py-4 text-sm font-semibold text-slate-900">
                                    ${{ number_format((float) $change->new_price, 2) }}
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-700">
                                    {{ $change->changedBy?->name ?? 'System' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500">
                                    No price changes recorded yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Quick actions</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Common admin shortcuts for daily inventory work.
                    </p>
                </div>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('admin.instruments.create') }}"
                   class="rounded-lg border border-slate-200 px-4 py-4 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Add new instrument
                </a>

                <a href="{{ route('admin.inquiries.index') }}"
                   class="rounded-lg border border-slate-200 px-4 py-4 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Review inquiries
                </a>

                <a href="{{ route('admin.reservations.index') }}"
                   class="rounded-lg border border-slate-200 px-4 py-4 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Review reservations
                </a>

                <a href="{{ route('admin.builders.index') }}"
                   class="rounded-lg border border-slate-200 px-4 py-4 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Manage catalogs
                </a>
            </div>
        </section>
    </div>
</x-app-layout>
