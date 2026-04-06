<x-layouts.admin>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Inquiry Details</h2>
                <p class="text-sm text-slate-500">Review the inquiry and update its status or assignment.</p>
            </div>

            <a href="{{ route('admin.inquiries.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to inquiries
            </a>
        </div>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card title="Customer Details">
                <dl class="mt-4 grid gap-5 sm:grid-cols-2">
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
            </x-ui.card>

            <x-ui.card title="Instrument">
                <div class="mt-2 flex flex-col gap-5 sm:flex-row">
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
                            class="mt-3 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-800"
                            target="_blank" rel="noopener noreferrer">
                            View storefront page
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div>
            <x-ui.card title="Manage Inquiry">
                <form action="{{ route('admin.inquiries.update', $inquiry) }}" method="POST" class="mt-4 space-y-5">
                    @csrf
                    @method('PATCH')

                    <x-ui.select
                        label="Status"
                        name="status"
                        :error="$errors->get('status')"
                    >
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $inquiry->status) === $status)>
                                {{ str($status)->replace('_', ' ')->title() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select
                        label="Assigned admin"
                        name="assigned_admin_id"
                        :error="$errors->get('assigned_admin_id')"
                    >
                        <option value="">Unassigned</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}" @selected((string) old('assigned_admin_id', $inquiry->assigned_admin_id) === (string) $admin->id)>
                                {{ $admin->name }} ({{ $admin->email }})
                            </option>
                        @endforeach
                    </x-ui.select>

                    <div class="rounded-lg bg-slate-50 p-4 text-sm text-slate-600">
                        <p><span class="font-medium text-slate-800">Created:</span>
                            {{ $inquiry->created_at?->format('Y-m-d H:i') }}</p>
                        <p class="mt-2"><span class="font-medium text-slate-800">Current status:</span>
                            {{ str($inquiry->status)->replace('_', ' ')->title() }}</p>
                    </div>

                    <x-ui.button type="submit" class="w-full justify-center">
                        Update inquiry
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
