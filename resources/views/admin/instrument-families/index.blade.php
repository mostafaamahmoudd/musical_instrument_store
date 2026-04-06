<x-layouts.admin>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Instrument Families</h2>
                <p class="text-sm text-slate-500">Curate families that group related instruments.</p>
            </div>
            <x-ui.button href="{{ route('admin.instrument-families.create') }}">Create Family</x-ui.button>
        </div>
    </x-slot>

    <x-ui.card padding="none">
        <div class="p-6">
            @if ($instrumentFamilies->count())
                <x-ui.table>
                    <x-slot name="head">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="py-3 pe-4">Name</th>
                            <th class="py-3 pe-4">Slug</th>
                            <th class="py-3 pe-4">Description</th>
                            <th class="py-3 text-right">Actions</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($instrumentFamilies as $family)
                            <tr>
                                <td class="py-3 pe-4">{{ $family->name }}</td>
                                <td class="py-3 pe-4">{{ $family->slug }}</td>
                                <td class="py-3 pe-4">{{ $family->description ?: '-' }}</td>
                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.instrument-families.edit', $family) }}"
                                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Edit</a>

                                        <form action="{{ route('admin.instrument-families.destroy', $family) }}"
                                              method="POST" onsubmit="return confirm('Delete this instrument family?');">
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
                    {{ $instrumentFamilies->links() }}
                </div>
            @else
                <x-ui.empty-state
                    title="No instrument families yet"
                    description="Add a family to keep inventory organized."
                >
                    <x-slot name="action">
                        <x-ui.button href="{{ route('admin.instrument-families.create') }}">Create Family</x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </div>
    </x-ui.card>
</x-layouts.admin>
