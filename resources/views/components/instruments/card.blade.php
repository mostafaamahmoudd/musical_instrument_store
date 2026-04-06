@props([
    'instrument',
])

@php
    $spec = $instrument?->spec;
    $imageUrl = $instrument?->getFirstMediaUrl('gallery', 'thumb');
@endphp

<a href="{{ route('storefront.instruments.show', $instrument) }}"
    class="group block overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:shadow-lg">
    <div class="aspect-[4/3] bg-slate-100">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}"
                alt="{{ trim(($spec?->builder?->name ?? '') . ' ' . ($spec?->model ?? 'Instrument')) }}"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]">
        @else
            <div class="flex h-full w-full items-center justify-center text-sm text-slate-400">
                No image
            </div>
        @endif
    </div>

    <div class="p-4">
        <p class="text-xs uppercase tracking-wide text-slate-500">
            {{ $spec?->instrumentFamily?->name ?? 'Instrument Family' }}
        </p>

        <h3 class="mt-1 text-lg font-semibold text-slate-900">
            {{ $spec?->builder?->name ?? 'Unknown Builder' }}
            {{ $spec?->model ?? '' }}
        </h3>

        <p class="mt-1 text-sm text-slate-600">
            {{ $spec?->instrumentType?->name ?? 'Type not set' }}
        </p>

        <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
            @if ($spec?->topWood?->name)
                <span class="rounded-full bg-slate-100 px-2 py-1">Top: {{ $spec->topWood->name }}</span>
            @endif

            @if ($spec?->backWood?->name)
                <span class="rounded-full bg-slate-100 px-2 py-1">Back: {{ $spec->backWood->name }}</span>
            @endif
        </div>

        <div class="mt-4 flex items-center justify-between">
            <span class="text-lg font-bold text-slate-900">
                ${{ number_format((float) $instrument->price, 2) }}
            </span>

            <div class="flex items-center gap-2">
                <x-ui.badge variant="neutral">
                    {{ ucfirst($instrument->condition) }}
                </x-ui.badge>
            </div>
        </div>
    </div>
</a>
