<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Inquiries</h2>
            <p class="text-sm text-slate-500">Review and manage customer instrument inquiries.</p>
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
                <x-ui.button href="{{ route('admin.inquiries.index') }}" variant="secondary">Reset</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card padding="none">
        @if ($inquiries->count())
            <x-ui.table>
                <x-slot name="head">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Instrument</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Assigned Admin</th>
                        <th class="px-4 py-3">Created</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach ($inquiries as $inquiry)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-slate-900">{{ $inquiry->name }}</div>
                                <div class="text-sm text-slate-500">{{ $inquiry->email }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                {{ $inquiry->instrument?->spec?->builder?->name }}
                                {{ $inquiry->instrument?->spec?->model }}
                            </td>
                            <td class="px-4 py-4">
                                <x-ui.badge :status="$inquiry->status" />
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                {{ $inquiry->assignedAdmin?->name ?? 'Unassigned' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-500">
                                {{ $inquiry->created_at?->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('admin.inquiries.show', $inquiry) }}"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-ui.table>

            <div class="p-6">
                {{ $inquiries->links() }}
            </div>
        @else
            <div class="p-6">
                <x-ui.empty-state
                    title="No inquiries found"
                    description="New inquiries will appear here when customers reach out."
                />
            </div>
        @endif
    </x-ui.card>
</x-layouts.admin>
