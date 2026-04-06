<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Create Instrument Family</h2>
            <p class="text-sm text-slate-500">Add a new family grouping.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.instrument-families.store') }}">
            @csrf
            @include('admin.instrument-families._form', ['submitLabel' => 'Create Family'])
        </form>
    </x-ui.card>
</x-layouts.admin>
