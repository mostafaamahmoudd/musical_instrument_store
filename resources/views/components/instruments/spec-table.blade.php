@props([
    'instrument',
])

@php
    $spec = $instrument?->spec;
    $rows = [
        ['Condition', $instrument?->condition ? ucfirst($instrument->condition) : 'N/A'],
        ['Stock Status', $instrument?->stock_status ? ucfirst($instrument->stock_status) : 'N/A'],
        ['Year Made', $instrument?->year_made?->format('Y') ?? 'N/A'],
        ['Number of Strings', $spec?->num_strings ?? 'N/A'],
        ['Back Wood', $spec?->backWood?->name ?? 'N/A'],
        ['Top Wood', $spec?->topWood?->name ?? 'N/A'],
        ['Style', $spec?->style ?? 'N/A'],
        ['Finish', $spec?->finish ?? 'N/A'],
    ];
@endphp

<x-ui.card title="Specifications" padding="none">
    <x-ui.table>
        <x-slot name="body">
            @foreach ($rows as $row)
                <tr>
                    <td class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        {{ $row[0] }}
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-slate-900">
                        {{ $row[1] }}
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-ui.table>
</x-ui.card>
