<x-layouts.app :title="__('Create Item')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">

        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Form Header --}}
            <div class="mb-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Item Details</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Isi form di bawah untuk membuat item baru.</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('item.store',['id'=>$purchase->id]) }}" class="flex flex-col gap-6">
                @csrf
                <x-errors />

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <x-input
                        name="item_description"
                        label="Deskripsi Item"
                        placeholder="e.g., Besi Beton"
                        value="{{ old('item_description') }}"
                        corner-hint="Wajib diisi"
                    />

                    <x-input
                        name="unit"
                        label="Satuan"
                        placeholder="e.g., kg, m, pcs"
                        value="{{ old('unit') }}"
                    />

                  

                    <x-input
                        name="weight"
                        label="Total Weight"
                        type="number"
                        placeholder="e.g., 500"
                        value="{{ old('weight') }}"
                    />

                    <x-input
                        name="kg_per_item"
                        label="KG per Item"
                        type="number"
                        step="any"
                        placeholder="e.g., 5"
                        value="{{ old('kg_per_item') }}"
                    />

  
<x-input
    name="u_price"
    label="Unit Price"
    type="number"
    step="any"
    placeholder="e.g., 15000"
    value="{{ old('u_price') }}"
    id="u_price"
/>

<x-input
    name="qty"
    label="Quantity"
    type="number"
    placeholder="e.g., 100"
    value="{{ old('qty') }}"
    id="qty"
/>
<x-input
    name="amount"
    label="Amount"
    type="number"
    step="any"
    placeholder="e.g., 1500000"
    value="{{ old('amount') }}"
    id="amount"
    readonly
/>

                </div>

                {{-- Footer --}}
                <div class="mt-4 flex justify-end gap-x-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <x-button label="Batal" flat href="{{ route('purchase.show',['id'=>$purchase->id]) }}" />
                    <x-button label="Simpan Item" type="submit" primary />
                </div>
                
            </form>
        </div>
    </div>
    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qty = document.getElementById('qty');
        const uPrice = document.getElementById('u_price');
        const amount = document.getElementById('amount');

        function calculateAmount() {
            const q = parseFloat(qty.value) || 0;
            const p = parseFloat(uPrice.value) || 0;
            amount.value = q * p;
        }

        qty.addEventListener('input', calculateAmount);
        uPrice.addEventListener('input', calculateAmount);
    });
</script>
@endpush

</x-layouts.app>
