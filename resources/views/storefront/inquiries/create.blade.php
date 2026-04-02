<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Instrument Inquiry
                </h2>
                <p class="text-sm text-gray-600">
                    Ask about availability, details, or next steps.
                </p>
            </div>

            <a href="{{ route('storefront.instruments.show', $instrument) }}"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to instrument
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-1">
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
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
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h1 class="text-2xl font-bold text-slate-900">Ask about this instrument</h1>
                        <p class="mt-2 text-sm text-slate-600">
                            Send a message and the store team can follow up with details, availability, or next steps.
                        </p>

                        <form action="{{ route('storefront.inquiries.store', $instrument) }}" method="POST"
                            class="mt-6 space-y-5">
                            @csrf

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                                    <input id="name" name="name" type="text" value="{{ old('name', $user?->name) }}"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-0">
                                    @error('name')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                                    <input id="email" name="email" type="email"
                                        value="{{ old('email', $user?->email) }}"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-0">
                                    @error('email')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $user?->phone) }}"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-0">
                                @error('phone')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subject" class="mb-1 block text-sm font-medium text-slate-700">Subject</label>
                                <input id="subject" name="subject" type="text"
                                    value="{{ old('subject', 'Inquiry about ' . trim(($instrument->spec?->builder?->name ?? '') . ' ' . ($instrument->spec?->model ?? ''))) }}"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-0">
                                @error('subject')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="message" class="mb-1 block text-sm font-medium text-slate-700">Message</label>
                                <textarea id="message" name="message" rows="6"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-0">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit"
                                    class="inline-flex items-center rounded-md bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                                    Send inquiry
                                </button>

                                <a href="{{ route('storefront.instruments.show', $instrument) }}"
                                    class="inline-flex items-center rounded-md border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
