<x-layouts.app :title="__('Create Item')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">

        {{-- Card Container --}}
        <div class="flex h-full w-full flex-col gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Form Header --}}
            <div class="mb-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Item Details</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Isi form di bawah untuk membuat item baru. Klik "Tambah Item" untuk menambahkan lebih dari satu.</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('purchase.item.store', ['id' => $purchase->id]) }}" class="flex flex-col gap-6">
                @csrf
                <x-errors />

                {{-- Container for dynamic item rows --}}
                <div id="item-rows-container" class="flex flex-col gap-8">
                    {{-- JavaScript akan menambahkan baris item di sini --}}
                </div>

                {{-- Action Buttons for Form --}}
                <div class="flex justify-start pt-4">
                    <x-button type="button" id="add-item-btn" label="Tambah Item" secondary icon="plus" />
                </div>


                {{-- Footer --}}
                <div class="mt-4 flex justify-end gap-x-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <x-button label="Batal" flat :href="route('purchase.show', ['id' => $purchase->id])" />
                    <x-button label="Simpan Semua Item" type="submit" primary />
                </div>
            </form>
        </div>
    </div>

    {{-- Template for a single item row (hidden) --}}
    <div id="item-row-template" class="hidden rounded-lg border border-gray-300 dark:border-gray-600 p-4">
        <div class="flex justify-between items-center mb-4">
             <h3 class="font-semibold text-gray-700 dark:text-gray-300">Item Details</h3>
            {{-- Kode Baru (Perbaikan) --}}
<button type="button" class="remove-item-btn flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white transition hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800" title="Hapus Item">
    {{-- SVG untuk ikon tong sampah --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
    </svg>
</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-input name="items[__INDEX__][item_description]" label="Deskripsi Item" placeholder="e.g., Besi Beton" />
            <x-input name="items[__INDEX__][unit]" label="Satuan" placeholder="e.g., kg, m, pcs" />
            <x-input name="items[__INDEX__][qty]" label="Quantity" type="number" placeholder="e.g., 100" class="qty-input" />
            <x-input name="items[__INDEX__][u_price]" label="Unit Price" placeholder="e.g., 15.000" class="u-price-input" />
            <x-input name="items[__INDEX__][amount]" label="Amount" placeholder="e.g., 1.500.000" class="amount-input" readonly />
            <div class="grid grid-cols-2 gap-4">
                <x-input name="items[__INDEX__][weight]" label="Total Weight" type="number" placeholder="e.g., 500" />
                <x-input name="items[__INDEX__][kg_per_item]" label="KG per Item" type="number" step="any" placeholder="e.g., 5" />
            </div>
        </div>
    </div>


    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('item-rows-container');
        const addButton = document.getElementById('add-item-btn');
        const template = document.getElementById('item-row-template');
        let itemIndex = 0;

        // Fungsi untuk memformat angka menjadi format Rupiah (lebih modern dan andal)
        function formatRupiah(number) {
            if (isNaN(number)) return '';
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        // Fungsi untuk mengubah format Rupiah kembali ke angka
        function parseRupiah(rupiahString) {
            // Menghapus semua karakter kecuali angka
            return parseFloat(String(rupiahString).replace(/[^\d]/g, '')) || 0;
        }
        
        // Fungsi untuk menambah baris item baru
        function addNewRow() {
            const newRow = template.cloneNode(true);
            newRow.removeAttribute('id');
            newRow.classList.remove('hidden');
            newRow.innerHTML = newRow.innerHTML.replace(/__INDEX__/g, itemIndex);
            container.appendChild(newRow);
            itemIndex++;
        }

        // Tambahkan baris pertama saat halaman dimuat
        addNewRow();

        // Event listener untuk tombol "Tambah Item"
        addButton.addEventListener('click', addNewRow);

        // Event delegation untuk menangani input dan tombol hapus di setiap baris
        container.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.remove-item-btn')) {
                // Hanya hapus jika lebih dari satu baris tersisa
                if (container.children.length > 1) {
                    e.target.closest('.rounded-lg').remove();
                } else {
                    alert('Minimal harus ada satu item.');
                }
            }
        });
        
        // Event listener untuk kalkulasi otomatis
        container.addEventListener('input', function(e) {
            const row = e.target.closest('.rounded-lg');
            if (!row) return;
            
            // Cek apakah input berasal dari field qty atau price
            if (e.target.closest('.qty-input') || e.target.closest('.u-price-input')) {
                
                // --- PERBAIKAN DI SINI ---
                // Kita tambahkan ' input' untuk menargetkan tag <input> di dalam div
                const qtyInput = row.querySelector('.qty-input input');
                const priceInput = row.querySelector('.u-price-input input');
                const amountInput = row.querySelector('.amount-input input');
                
                const qty = parseFloat(qtyInput.value) || 0;
                const price = parseRupiah(priceInput.value);
                
                const amount = qty * price;

                // Set nilai amount yang sudah diformat
                amountInput.value = formatRupiah(amount);
            }
        });

         // Event listener untuk format harga saat user selesai mengetik (onblur)
         container.addEventListener('focusout', function(e) {
            if (e.target.closest('.u-price-input')) {
                const inputElement = e.target.closest('.u-price-input input');
                const numericValue = parseRupiah(inputElement.value);
                inputElement.value = formatRupiah(numericValue);
            }
        });
    });
</script>
@endpush
</x-layouts.app>