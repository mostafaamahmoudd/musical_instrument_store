<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">My Inquiries</h2>
            <p class="text-sm text-slate-500">Track your submitted instrument questions and their status.</p>
        </div>
    </x-slot>

    <x-ui.card padding="none">
        @if ($inquiries->count())
            <div class="p-6">
                <div class="mb-6 text-sm text-slate-600">
                    Showing {{ $inquiries->firstItem() }}-{{ $inquiries->lastItem() }} of
                    {{ $inquiries->total() }} inquiries.
                </div>
            </div>

            <x-ui.table>
                <x-slot name="head">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3">Instrument</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Submitted</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach ($inquiries as $inquiry)
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                <div class="font-medium text-slate-900">
                                    {{ $inquiry->instrument?->spec?->builder?->name }}
                                    {{ $inquiry->instrument?->spec?->model }}
                                </div>
                                <div class="text-slate-500">
                                    {{ $inquiry->instrument?->spec?->instrumentType?->name }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <x-ui.badge :status="$inquiry->status" />
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-500">
                                {{ $inquiry->created_at?->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('storefront.inquiries.show', $inquiry) }}"
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
                    title="No inquiries yet"
                    description="Browse the inventory and submit an inquiry from an instrument page."
                >
                    <x-slot name="action">
                        <x-ui.button href="{{ route('storefront.instruments.index') }}">Browse inventory</x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            </div>
        @endif
    </x-ui.card>
</x-app-layout>
