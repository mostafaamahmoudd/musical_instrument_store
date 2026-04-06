<x-layouts.admin>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Admin Dashboard</p>
            <h2 class="mt-1 text-2xl font-semibold text-slate-900">Overview</h2>
        </div>
    </x-slot>

    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Overview of inventory, customer requests, and recent admin activity.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-ui.button href="{{ route('admin.instruments.index') }}">
                    Manage Instruments
                </x-ui.button>

                <x-ui.button href="{{ route('admin.inquiries.index') }}" variant="secondary">
                    View Inquiries
                </x-ui.button>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <x-ui.card padding="sm">
                <p class="text-sm font-medium text-slate-500">Total instruments</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($metrics['total_instruments']) }}</p>
            </x-ui.card>

            <x-ui.card padding="sm">
                <p class="text-sm font-medium text-slate-500">Published instruments</p>
                <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($metrics['published_instruments']) }}</p>
            </x-ui.card>

            <x-ui.card padding="sm">
                <p class="text-sm font-medium text-slate-500">Available instruments</p>
                <p class="mt-3 text-3xl font-bold text-emerald-600">{{ number_format($metrics['available_instruments']) }}</p>
            </x-ui.card>

            <x-ui.card padding="sm">
                <p class="text-sm font-medium text-slate-500">Pending inquiries</p>
                <p class="mt-3 text-3xl font-bold text-amber-600">{{ number_format($metrics['pending_inquiries']) }}</p>
            </x-ui.card>

            <x-ui.card padding="sm">
                <p class="text-sm font-medium text-slate-500">Pending reservations</p>
                <p class="mt-3 text-3xl font-bold text-sky-600">{{ number_format($metrics['pending_reservations']) }}</p>
            </x-ui.card>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-ui.card title="Latest audit activity" description="Recent create, update, and delete actions from admin workflows." padding="none">
                <x-ui.table>
                    <x-slot name="head">
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
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($latestAuditLogs as $log)
                            <tr>
                                <td class="px-5 py-4 text-sm text-slate-700">
                                    {{ $log->user?->name ?? 'System' }}
                                </td>

                                <td class="px-5 py-4">
                                    <x-ui.badge :status="$log->action">
                                        {{ ucfirst($log->action) }}
                                    </x-ui.badge>
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
                    </x-slot>
                </x-ui.table>
            </x-ui.card>

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
</x-layouts.admin>
