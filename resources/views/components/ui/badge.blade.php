@props([
    'variant' => 'neutral',
    'status' => null,
])

@php
    $key = strtolower($status ?? $variant);

    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700',
        'available' => 'bg-emerald-100 text-emerald-700',
        'reserved' => 'bg-amber-100 text-amber-700',
        'sold' => 'bg-slate-200 text-slate-700',
        'hidden' => 'bg-slate-200 text-slate-600',
        'new' => 'bg-sky-100 text-sky-700',
        'in_progress' => 'bg-amber-100 text-amber-700',
        'created' => 'bg-emerald-100 text-emerald-700',
        'updated' => 'bg-amber-100 text-amber-700',
        'deleted' => 'bg-rose-100 text-rose-700',
        'closed' => 'bg-emerald-100 text-emerald-700',
        'spam' => 'bg-rose-100 text-rose-700',
        'pending' => 'bg-amber-100 text-amber-700',
        'approved' => 'bg-emerald-100 text-emerald-700',
        'rejected' => 'bg-rose-100 text-rose-700',
        'expired' => 'bg-slate-200 text-slate-700',
        'cancelled' => 'bg-slate-200 text-slate-700',
        'info' => 'bg-sky-100 text-sky-700',
        'success' => 'bg-emerald-100 text-emerald-700',
        'warning' => 'bg-amber-100 text-amber-700',
        'danger' => 'bg-rose-100 text-rose-700',
    ];

    $classes = $variants[$key] ?? $variants['neutral'];
    $label = $slot->isNotEmpty() ? $slot : str($status ?? $variant)->replace('_', ' ')->title();
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {$classes}"]) }}>
    {{ $label }}
</span>
