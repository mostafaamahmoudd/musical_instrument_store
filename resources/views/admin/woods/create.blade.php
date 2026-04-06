<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Create Wood</h2>
            <p class="text-sm text-slate-500">Add a new tonewood option.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.woods.store') }}">
            @csrf
            @include('admin.woods._form', ['submitLabel' => 'Create Wood'])
        </form>
    </x-ui.card>
</x-layouts.admin>
