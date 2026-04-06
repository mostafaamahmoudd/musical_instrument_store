<x-layouts.admin>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Edit Instrument</h2>
            <p class="text-sm text-slate-500">Update instrument details and inventory settings.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.instruments.update', $instrument) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.instruments._form', ['submitLabel' => 'Update Instrument'])
        </form>
    </x-ui.card>
</x-layouts.admin>
