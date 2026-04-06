@props([
    'title' => null,
    'description' => null,
    'padding' => 'md',
])

@php
    $paddingMap = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];

    $contentPadding = $paddingMap[$padding] ?? $paddingMap['md'];
@endphp

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm']) }}>
    @if (isset($header) || $title || $description)
        <div class="border-b border-slate-200 px-6 py-4">
            @isset($header)
                {{ $header }}
            @else
                @if ($title)
                    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                @endif
                @if ($description)
                    <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                @endif
            @endisset
        </div>
    @endif

    <div class="{{ $contentPadding }}">
        {{ $slot }}
    </div>
</div>
