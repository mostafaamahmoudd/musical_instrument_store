<x-layouts.admin>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Instruments</h2>
                <p class="text-sm text-slate-500">Manage inventory listings and publishing status.</p>
            </div>

            <x-ui.button href="{{ route('admin.instruments.create') }}">
                Create Instrument
            </x-ui.button>
        </div>
    </x-slot>

    <x-ui.card padding="none">
        <div class="p-6">
            @if ($instruments->count())
                <x-ui.table>
                    <x-slot name="head">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="py-3 pe-4">Image</th>
                            <th class="py-3 pe-4">Serial</th>
                            <th class="py-3 pe-4">Builder</th>
                            <th class="py-3 pe-4">Model</th>
                            <th class="py-3 pe-4">Type</th>
                            <th class="py-3 pe-4">Price</th>
                            <th class="py-3 pe-4">Condition</th>
                            <th class="py-3 pe-4">Status</th>
                            <th class="py-3 pe-4">Featured</th>
                            <th class="py-3 text-right">Actions</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($instruments as $instrument)
                            <tr>
                                <td class="py-3 pe-4">
                                    @if ($instrument->getFirstMediaUrl('gallery', 'thumb'))
                                        <img src="{{ $instrument->getFirstMediaUrl('gallery', 'thumb') }}"
                                            alt="Instrument thumbnail"
                                            class="h-14 w-14 rounded-lg object-cover">
                                    @else
                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 text-xs text-slate-500">
                                            No image
                                        </div>
                                    @endif
                                </td>

                                <td class="py-3 pe-4">{{ $instrument->serial_number }}</td>
                                <td class="py-3 pe-4">{{ $instrument->spec?->builder?->name ?? '-' }}</td>
                                <td class="py-3 pe-4">{{ $instrument->spec?->model ?? '-' }}</td>
                                <td class="py-3 pe-4">{{ $instrument->spec?->instrumentType?->name ?? '-' }}</td>
                                <td class="py-3 pe-4">${{ number_format((float) $instrument->price, 2) }}</td>
                                <td class="py-3 pe-4 capitalize">
                                    <x-ui.badge variant="neutral">
                                        {{ $instrument->condition }}
                                    </x-ui.badge>
                                </td>
                                <td class="py-3 pe-4 capitalize">
                                    <x-ui.badge :status="$instrument->stock_status" />
                                </td>

                                <td class="py-3 pe-4">
                                    @if ($instrument->featured)
                                        <x-ui.badge variant="success">Yes</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="neutral">No</x-ui.badge>
                                    @endif
                                </td>

                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.instruments.edit', $instrument) }}"
                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('admin.instruments.destroy', $instrument) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this instrument?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-800">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-ui.table>

                <div class="mt-6">
                    {{ $instruments->links() }}
                </div>
            @else
                <x-ui.empty-state
                    title="No instruments found"
                    description="Create a new instrument to start building the catalog."
                >
                    <x-slot name="action">
                        <x-ui.button href="{{ route('admin.instruments.create') }}">Create Instrument</x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </div>
    </x-ui.card>
</x-layouts.admin>
