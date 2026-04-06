@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'error' => null,
    'help' => null,
])

@php
    $fieldId = $id ?? $name;
    $errorMessage = is_array($error) ? ($error[0] ?? null) : $error;
    $hasError = filled($errorMessage);
    $describedBy = collect([
        $help ? $fieldId.'-help' : null,
        $hasError ? $fieldId.'-error' : null,
    ])->filter()->implode(' ');

    $base = 'block w-full rounded-lg border bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500';
    $state = $hasError
        ? 'border-rose-300 focus:border-rose-400 focus:ring-rose-200'
        : 'border-slate-300 focus:border-slate-500 focus:ring-slate-200';
@endphp

<div class="space-y-1">
    @if ($label)
        <label for="{{ $fieldId }}" class="text-sm font-medium text-slate-700">
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $fieldId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if (! is_null($value)) value="{{ $value }}" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($hasError) aria-invalid="true" @endif
        {{ $attributes->merge(['class' => $base.' '.$state]) }}
    >

    @if ($help)
        <p id="{{ $fieldId }}-help" class="text-xs text-slate-500">{{ $help }}</p>
    @endif

    @if ($hasError)
        <p id="{{ $fieldId }}-error" class="text-xs text-rose-600">{{ $errorMessage }}</p>
    @endif
</div>
