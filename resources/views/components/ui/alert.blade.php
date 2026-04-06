@props([
    'variant' => 'info',
    'title' => null,
    'message' => null,
])

@php
    $variantStyles = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'error' => 'border-rose-200 bg-rose-50 text-rose-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'info' => 'border-sky-200 bg-sky-50 text-sky-800',
    ];

    $styles = $variantStyles[$variant] ?? $variantStyles['info'];
@endphp

<div role="alert" {{ $attributes->merge(['class' => "rounded-xl border px-4 py-3 text-sm {$styles}"]) }}>
    @if ($title)
        <p class="text-sm font-semibold">{{ $title }}</p>
    @endif

    @if ($message)
        <p class="text-sm">{{ $message }}</p>
    @endif

    {{ $slot }}
</div>
