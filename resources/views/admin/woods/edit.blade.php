<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Edit Wood</h2>
            <p class="text-sm text-slate-500">Update tonewood details.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.woods.update', $wood) }}">
            @csrf
            @method('PUT')
            @include('admin.woods._form', ['submitLabel' => 'Update Wood'])
        </form>
    </x-ui.card>
</x-layouts.admin>
