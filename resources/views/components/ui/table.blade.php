@props([
    'striped' => true,
])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-slate-200 text-sm']) }}>
        @isset($head)
            <thead class="bg-slate-50">
                {{ $head }}
            </thead>
        @endisset

        <tbody @class([
            'divide-y divide-slate-200 bg-white text-slate-700' => true,
            '[&>tr:nth-child(even)]:bg-slate-50/60' => $striped,
        ])>
            {{ $body ?? $slot }}
        </tbody>
    </table>
</div>
