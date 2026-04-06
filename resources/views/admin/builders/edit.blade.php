<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Edit Builder</h2>
            <p class="text-sm text-slate-500">Update builder details and status.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.builders.update', $builder) }}">
            @csrf
            @method('PUT')
            @include('admin.builders._form', ['submitLabel' => 'Update Builder'])
        </form>
    </x-ui.card>
</x-layouts.admin>
