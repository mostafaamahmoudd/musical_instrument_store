@props([
    'title' => 'Nothing here yet',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center']) }}>
    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>

    @if ($description)
        <p class="mt-2 text-sm text-slate-600">{{ $description }}</p>
    @endif

    @if (isset($action))
        <div class="mt-6">
            {{ $action }}
        </div>
    @endif
</div>
