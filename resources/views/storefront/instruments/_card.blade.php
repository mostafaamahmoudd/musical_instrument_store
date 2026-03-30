<a href="{{ route('storefront.instruments.show', $instrument) }}"
   class="block bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition">
    <div class="aspect-[4/3] bg-gray-100">
        @if ($instrument->getFirstMediaUrl('gallery', 'thumb'))
            <img
                src="{{ $instrument->getFirstMediaUrl('gallery', 'thumb') }}"
                alt="{{ trim(($instrument->spec?->builder?->name ?? '') . ' ' . ($instrument->spec?->model ?? 'Instrument')) }}"
                class="h-full w-full object-cover"
            >
        @else
            <div class="h-full w-full flex items-center justify-center text-sm text-gray-500">
                No image
            </div>
        @endif
    </div>

    <div class="p-4">
        <p class="text-xs uppercase tracking-wide text-gray-500">
            {{ $instrument->spec?->instrumentFamily?->name ?? 'Instrument Family' }}
        </p>

        <h3 class="mt-1 text-lg font-semibold text-gray-900">
            {{ $instrument->spec?->builder?->name ?? 'Unknown Builder' }}
            {{ $instrument->spec?->model ?? '' }}
        </h3>

        <p class="mt-1 text-sm text-gray-600">
            {{ $instrument->spec?->instrumentType?->name ?? 'Type not set' }}
        </p>

        <div class="mt-4 flex items-center justify-between">
            <span class="text-lg font-bold text-gray-900">
                {{ number_format((float) $instrument->price, 2) }}
            </span>

            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700 capitalize">
                {{ $instrument->condition }}
            </span>
        </div>
    </div>
</a>
