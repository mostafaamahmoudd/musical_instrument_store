<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Instruments</h2>
            <a href="{{ route('admin.instruments.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-black">
                Create Instrument
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
                    @if ($instruments->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="text-left text-sm text-gray-500">
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
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm text-gray-800">
                                    @foreach ($instruments as $instrument)
                                        <tr>
                                            <td class="py-3 pe-4">{{ $instrument->serial_number }}</td>
                                            <td class="py-3 pe-4">{{ $instrument->spec?->builder?->name ?? '-' }}</td>
                                            <td class="py-3 pe-4">{{ $instrument->spec?->model ?? '-' }}</td>
                                            <td class="py-3 pe-4">{{ $instrument->spec?->instrumentType?->name ?? '-' }}
                                            </td>
                                            <td class="py-3 pe-4">{{ number_format($instrument->price, 2) }}</td>
                                            <td class="py-3 pe-4 capitalize">{{ $instrument->condition }}</td>
                                            <td class="py-3 pe-4 capitalize">{{ $instrument->stock_status }}</td>
                                            <td class="py-3 pe-4">
                                                @if ($instrument->featured)
                                                    <span
                                                        class="rounded bg-green-100 px-2 py-1 text-xs text-green-700">Yes</span>
                                                @else
                                                    <span
                                                        class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-700">No</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-right">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('admin.instruments.edit', $instrument) }}"
                                                        class="text-indigo-600 hover:text-indigo-800">Edit</a>

                                                    <form
                                                        action="{{ route('admin.instruments.destroy', $instrument) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Delete this instrument?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $instruments->links() }}
                        </div>
                    @else
                        <p class="text-sm text-gray-600">No instruments found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
