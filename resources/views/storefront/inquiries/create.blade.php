<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Instrument Inquiry</h2>
                <p class="text-sm text-slate-500">Ask about availability, details, or next steps.</p>
            </div>

            <a href="{{ route('storefront.instruments.show', $instrument) }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to instrument
            </a>
        </div>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <x-ui.card padding="none">
                <div class="aspect-[4/3] bg-slate-100">
                    @php
                        $instrumentImage = $instrument->getFirstMediaUrl('gallery', 'preview');
                    @endphp
                    @if ($instrumentImage)
                        <img src="{{ $instrumentImage }}"
                            alt="{{ trim(($instrument->spec?->builder?->name ?? '') . ' ' . ($instrument->spec?->model ?? 'Instrument')) }}"
                            class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center text-sm text-slate-400">
                            No image
                        </div>
                    @endif
                </div>

                <div class="p-5">
                    <h3 class="text-lg font-semibold text-slate-900">
                        {{ $instrument->spec?->builder?->name }} {{ $instrument->spec?->model }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $instrument->spec?->instrumentFamily?->name }} ·
                        {{ $instrument->spec?->instrumentType?->name }}
                    </p>
                    <p class="mt-4 text-xl font-bold text-slate-900">
                        ${{ number_format((float) $instrument->price, 2) }}
                    </p>
                </div>
            </x-ui.card>
        </div>

        <div class="lg:col-span-2">
            <x-ui.card>
                <h1 class="text-2xl font-bold text-slate-900">Ask about this instrument</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Send a message and the store team can follow up with details, availability, or next steps.
                </p>

                <form action="{{ route('storefront.inquiries.store', $instrument) }}" method="POST"
                    class="mt-6 space-y-5">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-ui.input
                            label="Name"
                            name="name"
                            :value="old('name', $user?->name)"
                            :error="$errors->get('name')"
                        />

                        <x-ui.input
                            label="Email"
                            name="email"
                            type="email"
                            :value="old('email', $user?->email)"
                            :error="$errors->get('email')"
                        />
                    </div>

                    <x-ui.input
                        label="Phone"
                        name="phone"
                        :value="old('phone', $user?->phone)"
                        :error="$errors->get('phone')"
                    />

                    <x-ui.input
                        label="Subject"
                        name="subject"
                        :value="old('subject', 'Inquiry about ' . trim(($instrument->spec?->builder?->name ?? '') . ' ' . ($instrument->spec?->model ?? '')))"
                        :error="$errors->get('subject')"
                    />

                    <x-ui.textarea
                        label="Message"
                        name="message"
                        rows="6"
                        :error="$errors->get('message')"
                    >{{ old('message') }}</x-ui.textarea>

                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.button type="submit">Send inquiry</x-ui.button>
                        <x-ui.button href="{{ route('storefront.instruments.show', $instrument) }}" variant="secondary">
                            Cancel
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
