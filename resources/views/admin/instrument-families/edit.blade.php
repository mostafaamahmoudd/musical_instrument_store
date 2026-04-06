<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Edit Instrument Family</h2>
            <p class="text-sm text-slate-500">Update the family details.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.instrument-families.update', $instrumentFamily) }}">
            @csrf
            @method('PUT')
            @include('admin.instrument-families._form', ['submitLabel' => 'Update Family'])
        </form>
    </x-ui.card>
</x-layouts.admin>
