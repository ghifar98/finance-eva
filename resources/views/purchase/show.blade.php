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
                    {{-- <x-button label="Edit" primary href="{{ route('purchase.edit', $purchase->id) }}" /> --}}
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
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $purchase->created_at->format('d M Y H:i') }}
                    </dd>
                </div>
                 <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">status</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $purchase->status }}
                    </dd>
                </div>
       <div class="mt-6 overflow-auto w-full">
    <livewire:item-table :purchase-id="$purchase->id" />
</div>
<div class="mt-6 overflow-auto w-full">
    <form action="{{ route('purchase.updatestatus', $purchase->id) }}" method="POST">
        @csrf
        <x-errors />
        
    <div class="mb-4">
    <label for="account_id" class="block text-sm font-medium text-gray-700">Pilih Akun</label>
    <select id="account_id" name="account_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @foreach ($accounts as $id => $name)
            <option value="{{ $id }}" {{ $purchase->account_id == $id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>


<x-select
            name="status"
            label="Status"
            placeholder="e.g., Active, Pending, Stuck, Done"
            value="{{ old('status', $purchase->status) }}"
            corner-hint="Wajib diisi"
             :options="['belum disetuji', 'disetujui', 'ditolak']"
        />

        <x-button label="Update Status" type="submit" primary />

    </form>
</div>


            </div>
        </div>
    </div>
</x-layouts.app>
