<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $currentFamily ? $currentFamily->name . ' Inventory' : 'Inventory' }}
                </h2>
                <p class="text-sm text-gray-600">
                    Browse currently visible instruments in the storefront.
                </p>
            </div>

            <a href="{{ route('home') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to Home
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('storefront.instruments._filters')

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($instruments->count())
                        <div class="mb-6 text-sm text-gray-600">
                            Showing {{ $instruments->firstItem() }}-{{ $instruments->lastItem() }}
                            of {{ $instruments->total() }} instruments.
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                            @foreach ($instruments as $instrument)
                                @include('storefront.instruments._card', ['instrument' => $instrument])
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $instruments->onEachSide(1)->links() }}
                        </div>
                    @else
                        <div class="rounded-lg border border-dashed border-gray-300 p-10 text-center">
                            <h3 class="text-lg font-semibold text-gray-900">No instruments found</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Try adjusting your family, builder, condition, or sorting filters.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
