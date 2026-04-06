<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Create Builder</h2>
            <p class="text-sm text-slate-500">Add a new builder to the catalog.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.builders.store') }}">
            @csrf
            @include('admin.builders._form', ['submitLabel' => 'Create Builder'])
        </form>
    </x-ui.card>
</x-layouts.admin>
