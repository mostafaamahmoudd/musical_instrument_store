<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Create Instrument</h2>
            <p class="text-sm text-slate-500">Add a new instrument to the catalog.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.instruments.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.instruments._form', ['submitLabel' => 'Create Instrument'])
        </form>
    </x-ui.card>
</x-layouts.admin>
