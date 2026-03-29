<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Woods</h2>
            <a href="{{ route('admin.woods.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-black">
                Create Wood
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6">
                    @if ($woods->count())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="text-left text-sm text-gray-500">
                                    <th class="py-3 pe-4">Name</th>
                                    <th class="py-3 pe-4">Slug</th>
                                    <th class="py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm text-gray-800">
                                @foreach ($woods as $wood)
                                    <tr>
                                        <td class="py-3 pe-4">{{ $wood->name }}</td>
                                        <td class="py-3 pe-4">{{ $wood->slug }}</td>
                                        <td class="py-3 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.woods.edit', $wood) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                                <form action="{{ route('admin.woods.destroy', $wood) }}" method="POST" onsubmit="return confirm('Delete this wood?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-6">
                            {{ $woods->links() }}
                        </div>
                    @else
                        <p class="text-sm text-gray-600">No woods found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
