<x-layouts.app :title="__('Purchase Items & Status')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">

        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- View Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-700">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Items & Status - Purchase #: {{ $purchase->po_no ?? '-' }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage purchase items and update status.</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-x-2">
                    <x-button label="Back to Details" href="{{ route('purchase.show', $purchase->id) }}" />
                    <x-button label="Back to List" href="{{ route('purchase.index') }}" />
                </div>
            </div>

            {{-- Purchase Summary --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">PO Number</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $purchase->po_no }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Vendor</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $purchase->vendor->name ?? '-' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($purchase->total_amount ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <p class="font-semibold">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($purchase->status == 'disetujui') bg-green-100 text-green-800
                            @elseif($purchase->status == 'ditolak') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $purchase->status }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Purchase Items</h3>
                <div class="overflow-auto w-full">
                    <livewire:item-table :purchase-id="$purchase->id" />
                </div>
            </div>

            {{-- Update Status Form --}}
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Update Purchase Status</h3>
                
                <form action="{{ route('purchase.updatestatus', $purchase->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <x-errors />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Account Selection --}}
                        <div>
                            <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Akun</label>
                            <select id="account_id" name="account_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                @foreach ($accounts as $id => $name)
                                    <option value="{{ $id }}" {{ $purchase->account_id == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ✅ PERBAIKAN: Ganti x-select dengan HTML select biasa --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select status</option>
                                <option value="belum disetujui" {{ old('status', $purchase->status) == 'belum disetujui' ? 'selected' : '' }}>
                                    Belum Disetujui
                                </option>
                                <option value="disetujui" {{ old('status', $purchase->status) == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui
                                </option>
                                <option value="ditolak" {{ old('status', $purchase->status) == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Wajib diisi</p>
                        </div>
                    </div>

                    {{-- Add hidden input for redirect --}}
                    <input type="hidden" name="redirect_to" value="items">

                    <div class="flex justify-end">
                        <x-button label="Update Status" type="submit" primary />
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts.app>