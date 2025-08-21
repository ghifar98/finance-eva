<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase; // Pastikan model Purchase di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB Facade for transactions

class ItemController extends Controller
{
    public function create($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('item.create')->with('purchase', $purchase);
    }

    public function store(Request $request, $id)
    {
        // Validasi untuk array of items
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_description' => 'required|string|max:255',
            'items.*.unit' => 'required|string|max:50',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.weight' => 'nullable|numeric|min:0',
            'items.*.kg_per_item' => 'nullable|numeric|min:0',
            'items.*.u_price' => 'required|string', // Terima sebagai string karena format Rupiah
            // 'amount' tidak perlu divalidasi karena akan dihitung di backend
        ], [
            'items.*.item_description.required' => 'Deskripsi item wajib diisi.',
            'items.*.unit.required' => 'Satuan harus diisi.',
            'items.*.qty.required' => 'Jumlah harus diisi.',
            'items.*.u_price.required' => 'Harga satuan harus diisi.',
        ]);

        $purchase = Purchase::findOrFail($id);
        $totalAmountAdded = 0;
        $totalQtyAdded = 0;

        // Gunakan database transaction untuk memastikan semua data tersimpan atau tidak sama sekali
        DB::transaction(function () use ($request, $purchase, &$totalAmountAdded, &$totalQtyAdded) {
            foreach ($request->items as $itemData) {
                // 1. Bersihkan format Rupiah dari u_price sebelum perhitungan
                $unitPrice = (float) preg_replace('/[^\d]/', '', $itemData['u_price']);
                $quantity = (float) $itemData['qty'];
                
                // 2. Hitung amount di backend sebagai sumber kebenaran
                $amount = $quantity * $unitPrice;

                // 3. Buat item baru
                Item::create([
                    'purchase_id' => $purchase->id,
                    'item_description' => $itemData['item_description'],
                    'unit' => $itemData['unit'],
                    'qty' => $quantity,
                    'weight' => $itemData['weight'] ?? null,
                    'kg_per_item' => $itemData['kg_per_item'] ?? null,
                    'u_price' => $unitPrice,
                    'amount' => $amount,
                ]);

                // 4. Akumulasi total untuk diupdate ke purchase
                $totalAmountAdded += $amount;
                $totalQtyAdded += $quantity;
            }

            // 5. Update total di tabel Purchase setelah loop selesai
            $purchase->total_amount += $totalAmountAdded;
            $purchase->qty += $totalQtyAdded;
            $purchase->save();
        });

        return redirect()->route('purchase.show', ['id' => $id])->with('success', 'Item berhasil ditambahkan.');
    }
}