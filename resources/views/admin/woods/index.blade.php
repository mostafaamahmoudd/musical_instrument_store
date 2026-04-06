<x-layouts.admin>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Woods</h2>
                <p class="text-sm text-slate-500">Maintain available tonewood options.</p>
            </div>
            <x-ui.button href="{{ route('admin.woods.create') }}">Create Wood</x-ui.button>
        </div>
    </x-slot>

    <x-ui.card padding="none">
        <div class="p-6">
            @if ($woods->count())
                <x-ui.table>
                    <x-slot name="head">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="py-3 pe-4">Name</th>
                            <th class="py-3 pe-4">Slug</th>
                            <th class="py-3 pe-4">Origin</th>
                            <th class="py-3 pe-4">Status</th>
                            <th class="py-3 text-right">Actions</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($woods as $wood)
                            <tr>
                                <td class="py-3 pe-4">{{ $wood->name }}</td>
                                <td class="py-3 pe-4">{{ $wood->slug }}</td>
                                <td class="py-3 pe-4">{{ $wood->origin ?: '-' }}</td>
                                <td class="py-3 pe-4">
                                    <x-ui.badge :variant="$wood->is_active ? 'success' : 'danger'">
                                        {{ $wood->is_active ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </td>
                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.woods.edit', $wood) }}"
                                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Edit</a>

                                        <form action="{{ route('admin.woods.destroy', $wood) }}"
                                              method="POST" onsubmit="return confirm('Delete this wood?');">
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
                    {{ $woods->links() }}
                </div>
            @else
                <x-ui.empty-state
                    title="No woods found"
                    description="Create a wood entry to support inventory specs."
                >
                    <x-slot name="action">
                        <x-ui.button href="{{ route('admin.woods.create') }}">Create Wood</x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </div>
    </x-ui.card>
</x-layouts.admin>
