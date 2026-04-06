<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Create Instrument Type</h2>
            <p class="text-sm text-slate-500">Add a new instrument type.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.instrument-types.store') }}">
            @csrf
            @include('admin.instrument-types._form', ['submitLabel' => 'Create Type'])
        </form>
    </x-ui.card>
</x-layouts.admin>
