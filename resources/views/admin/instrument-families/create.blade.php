<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Instrument Family</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.instrument-families.store') }}">
                        @csrf
                        @include('admin.instrument-families._form', ['submitLabel' => 'Create Family'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
