<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Builder</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.builders.update', $builder) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.builders._form', ['submitLabel' => 'Update Builder'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
