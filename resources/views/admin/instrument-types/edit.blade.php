<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Edit Instrument Type</h2>
            <p class="text-sm text-slate-500">Update the instrument type details.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.instrument-types.update', $instrumentType) }}">
            @csrf
            @method('PUT')
            @include('admin.instrument-types._form', ['submitLabel' => 'Update Type'])
        </form>
    </x-ui.card>
</x-layouts.admin>
