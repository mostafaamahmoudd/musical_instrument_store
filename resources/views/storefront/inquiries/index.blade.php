<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    My Inquiries
                </h2>
                <p class="text-sm text-gray-600">
                    Track your submitted instrument questions and their status.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($inquiries->count())
                        <div class="mb-6 text-sm text-gray-600">
                            Showing {{ $inquiries->firstItem() }}-{{ $inquiries->lastItem() }} of
                            {{ $inquiries->total() }} inquiries.
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Instrument</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Submitted</th>
                                        <th class="px-4 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($inquiries as $inquiry)
                                        <tr>
                                            <td class="px-4 py-4 text-sm text-gray-700">
                                                <div class="font-medium text-gray-900">
                                                    {{ $inquiry->instrument?->spec?->builder?->name }}
                                                    {{ $inquiry->instrument?->spec?->model }}
                                                </div>
                                                <div class="text-gray-500">
                                                    {{ $inquiry->instrument?->spec?->instrumentType?->name }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span
                                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                                @class([
                                                    'bg-blue-100 text-blue-700' => $inquiry->status === 'new',
                                                    'bg-amber-100 text-amber-700' => $inquiry->status === 'in_progress',
                                                    'bg-emerald-100 text-emerald-700' => $inquiry->status === 'closed',
                                                    'bg-rose-100 text-rose-700' => $inquiry->status === 'spam',
                                                ])">
                                                    {{ str($inquiry->status)->replace('_', ' ')->title() }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500">
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
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $inquiries->links() }}
                        </div>
                    @else
                        <div class="rounded-lg border border-dashed border-gray-300 p-10 text-center">
                            <h3 class="text-lg font-semibold text-gray-900">No inquiries yet</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Browse the inventory and submit an inquiry from an instrument page.
                            </p>
                            <a href="{{ route('storefront.instruments.index') }}"
                                class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                Browse inventory
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
