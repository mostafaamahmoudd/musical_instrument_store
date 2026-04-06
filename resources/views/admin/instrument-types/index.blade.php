<x-layouts.admin>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Instrument Types</h2>
                <p class="text-sm text-slate-500">Define specific instrument types within each family.</p>
            </div>
            <x-ui.button href="{{ route('admin.instrument-types.create') }}">Create Type</x-ui.button>
        </div>
    </x-slot>

    <x-ui.card padding="none">
        <div class="p-6">
            @if ($instrumentTypes->count())
                <x-ui.table>
                    <x-slot name="head">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="py-3 pe-4">Name</th>
                            <th class="py-3 pe-4">Family</th>
                            <th class="py-3 pe-4">Slug</th>
                            <th class="py-3 text-right">Actions</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($instrumentTypes as $type)
                            <tr>
                                <td class="py-3 pe-4">{{ $type->name }}</td>
                                <td class="py-3 pe-4">{{ $type->instrumentFamily?->name ?? '-' }}</td>
                                <td class="py-3 pe-4">{{ $type->slug }}</td>
                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.instrument-types.edit', $type) }}"
                                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Edit</a>

                                        <form action="{{ route('admin.instrument-types.destroy', $type) }}"
                                              method="POST" onsubmit="return confirm('Delete this instrument type?');">
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
                    {{ $instrumentTypes->links() }}
                </div>
            @else
                <x-ui.empty-state
                    title="No instrument types yet"
                    description="Add types so instruments can be categorized more precisely."
                >
                    <x-slot name="action">
                        <x-ui.button href="{{ route('admin.instrument-types.create') }}">Create Type</x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </div>
    </x-ui.card>
</x-layouts.admin>
