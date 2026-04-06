@props([
    'instrument',
])

@php
    $gallery = $instrument?->getMedia('gallery') ?? collect();
    $mainImage = $instrument?->getFirstMediaUrl('gallery', 'preview');
@endphp

<div>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
        @if ($mainImage)
            <img
                src="{{ $mainImage }}"
                alt="{{ trim(($instrument?->spec?->builder?->name ?? '') . ' ' . ($instrument?->spec?->model ?? 'Instrument')) }}"
                class="h-[420px] w-full object-cover"
            >
        @else
            <div class="flex h-[420px] w-full items-center justify-center text-slate-500">
                No image available
            </div>
        @endif
    </div>

    @if ($gallery->count() > 1)
        <div class="mt-4 grid grid-cols-4 gap-3">
            @foreach ($gallery as $media)
                <div class="overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                    <img
                        src="{{ $media->getUrl('thumb') }}"
                        alt="Instrument gallery image"
                        class="h-24 w-full object-cover"
                    >
                </div>
            @endforeach
        </div>
    @endif
</div>
