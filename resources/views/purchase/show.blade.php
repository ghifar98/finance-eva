<x-layouts.app :title="__('Purchase Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">

        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- View Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Purchase #: {{ $purchase->po_no ?? '-' }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">A detailed view of the purchase information.</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-x-2">
                    <x-button label="Back" href="{{ route('purchase.index') }}" />
                    <x-button label="View Items & Status" primary href="{{ route('purchase.items', $purchase->id) }}" />
                </div>
            </div>

            {{-- Details Grid --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-2">

                {{-- Helper for displaying details --}}
                @php
                    function DetailItem($label, $value) {
                        return <<<HTML
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">$label</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{$value}</dd>
                        </div>
                        HTML;
                    }
                @endphp

                {!! DetailItem('Date', $purchase->date ?: '-') !!}
                {!! DetailItem('PO No', $purchase->po_no ?: '-') !!}
                {!! DetailItem('Company', $purchase->company ?: '-') !!}
                {!! DetailItem('Project', $purchase->project->project_name ?? '-') !!}
                {!! DetailItem('Vendor', $purchase->vendor->name ?? '-') !!}
                {!! DetailItem('Package', $purchase->package ?: '-') !!}
                {!! DetailItem('Representative', $purchase->rep_name ?: '-') !!}
                {!! DetailItem('Phone', $purchase->phone ?: '-') !!}
                {!! DetailItem('Qty', $purchase->qty ?: '-') !!}
                {!! DetailItem('Total Amount', 'Rp ' . number_format($purchase->total_amount ?? 0, 0, ',', '.')) !!}
                {!! DetailItem('Total PPN', 'Rp ' . number_format($purchase->total_ppn ?? 0, 0, ',', '.')) !!}
                {!! DetailItem('Balance', 'Rp ' . number_format($purchase->balance ?? 0, 0, ',', '.')) !!}

                {{-- Description / Other Info --}}
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $purchase->created_at->format('d M Y H:i') }}
                    </dd>
                </div>
                
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($purchase->status == 'disetujui') bg-green-100 text-green-800
                            @elseif($purchase->status == 'ditolak') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $purchase->status }}
                        </span>
                    </dd>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>