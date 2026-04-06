<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Inquiry Details</h2>
                <p class="text-sm text-slate-500">Review what you submitted and the current status.</p>
            </div>

            <a href="{{ route('storefront.inquiries.index') }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to inquiries
            </a>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">
                    {{ $inquiry->instrument?->spec?->builder?->name }}
                    {{ $inquiry->instrument?->spec?->model }}
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $inquiry->instrument?->spec?->instrumentFamily?->name }}
                    ·
                    {{ $inquiry->instrument?->spec?->instrumentType?->name }}
                </p>
            </div>

            <x-ui.badge :status="$inquiry->status" />
        </div>

        <div class="mt-4 grid gap-6 md:grid-cols-[160px_1fr]">
            <div class="aspect-square overflow-hidden rounded-lg bg-slate-100">
                @php
                    $instrumentImage = $inquiry->instrument?->getFirstMediaUrl('gallery', 'thumb');
                @endphp
                @if ($instrumentImage)
                    <img src="{{ $instrumentImage }}"
                        alt="Instrument image"
                        class="h-full w-full object-cover">
                @else
                    <div class="flex h-full items-center justify-center text-xs text-slate-400">
                        No image
                    </div>
                @endif
            </div>

            <div class="space-y-3 text-sm text-slate-700">
                <p>
                    <span class="font-medium text-slate-900">Submitted:</span>
                    {{ $inquiry->created_at?->format('Y-m-d H:i') }}
                </p>
                <p>
                    <span class="font-medium text-slate-900">Subject:</span>
                    {{ $inquiry->subject ?: '—' }}
                </p>
                <div class="rounded-lg bg-slate-50 p-4 text-sm text-slate-700">
                    <p class="font-medium text-slate-900">Message</p>
                    <p class="mt-2 leading-6">
                        {{ $inquiry->message }}
                    </p>
                </div>
                <a href="{{ route('storefront.instruments.show', $inquiry->instrument) }}"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    View instrument page
                </a>
            </div>
        </div>
    </x-ui.card>
</x-app-layout>
