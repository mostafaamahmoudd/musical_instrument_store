<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Inquiry Details
                </h2>
                <p class="text-sm text-gray-600">
                    Review the inquiry and update its status or assignment.
                </p>
            </div>

            <a href="{{ route('admin.inquiries.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to inquiries
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Customer Details</h3>

                        <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Customer name</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $inquiry->name }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $inquiry->email }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Phone</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $inquiry->phone ?: '—' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Linked user</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $inquiry->user?->name ?? 'Guest / not linked' }}</dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Subject</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $inquiry->subject ?: '—' }}</dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Message</dt>
                                <dd class="mt-1 rounded-lg bg-slate-50 p-4 text-sm leading-6 text-slate-800">
                                    {{ $inquiry->message }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Instrument</h3>

                        <div class="mt-4 flex gap-5">
                            <div class="h-28 w-28 shrink-0 overflow-hidden rounded-lg bg-slate-100">
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

                            <div>
                                <h4 class="text-base font-semibold text-slate-900">
                                    {{ $inquiry->instrument?->spec?->builder?->name }}
                                    {{ $inquiry->instrument?->spec?->model }}
                                </h4>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $inquiry->instrument?->spec?->instrumentFamily?->name }}
                                    ·
                                    {{ $inquiry->instrument?->spec?->instrumentType?->name }}
                                </p>
                                <p class="mt-3 text-sm text-slate-700">
                                    Price: ${{ number_format((float) $inquiry->instrument?->price, 2) }}
                                </p>
                                <a href="{{ route('storefront.instruments.show', $inquiry->instrument) }}"
                                    class="mt-3 inline-flex text-sm font-medium text-slate-900 hover:text-slate-700"
                                    target="_blank" rel="noopener noreferrer">
                                    View storefront page
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">Manage Inquiry</h3>

                        <form action="{{ route('admin.inquiries.update', $inquiry) }}" method="POST" class="mt-6 space-y-5">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                                <select id="status" name="status"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected(old('status', $inquiry->status) === $status)>
                                            {{ str($status)->replace('_', ' ')->title() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="assigned_admin_id" class="mb-1 block text-sm font-medium text-slate-700">Assigned
                                    admin</label>
                                <select id="assigned_admin_id" name="assigned_admin_id"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                                    <option value="">Unassigned</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}" @selected((string) old('assigned_admin_id', $inquiry->assigned_admin_id) === (string) $admin->id)>
                                            {{ $admin->name }} ({{ $admin->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_admin_id')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="rounded-lg bg-slate-50 p-4 text-sm text-slate-600">
                                <p><span class="font-medium text-slate-800">Created:</span>
                                    {{ $inquiry->created_at?->format('Y-m-d H:i') }}</p>
                                <p class="mt-2"><span class="font-medium text-slate-800">Current status:</span>
                                    {{ str($inquiry->status)->replace('_', ' ')->title() }}</p>
                            </div>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-md bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                                Update inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
