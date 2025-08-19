<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function create($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('item.create', compact('purchase'));
    }
    
    public function store(Request $request, $id)
    {
        $request->validate([
            'item_description' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'qty' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'kg_per_item' => 'nullable|numeric|min:0',
            'u_price' => 'required|numeric|min:0',
        ], [
            'item_description.required' => 'Deskripsi item wajib diisi.',
            'unit.required' => 'Satuan harus diisi.',
            'qty.required' => 'Jumlah harus diisi.',
            'u_price.required' => 'Harga satuan harus diisi.',
        ]);

        $amount = $request->qty * $request->u_price; // Calculate amount

        $data = $request->all();
        $data['purchase_id'] = $id;
        $data['amount'] = $amount;
        
        Item::create($data);

        // Update purchase totals
        $purchase = Purchase::findOrFail($id);
        $purchase->total_amount += $amount;
        $purchase->qty += $request->qty;
        $purchase->save();

        return redirect()->route('purchase.items', $id)->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit($purchaseId, $itemId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $item = Item::where('purchase_id', $purchaseId)->findOrFail($itemId);
        
        return view('item.edit', compact('purchase', 'item'));
    }

    public function update(Request $request, $purchaseId, $itemId)
    {
        $request->validate([
            'item_description' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'qty' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'kg_per_item' => 'nullable|numeric|min:0',
            'u_price' => 'required|numeric|min:0',
        ]);

        $item = Item::where('purchase_id', $purchaseId)->findOrFail($itemId);
        $oldAmount = $item->amount;
        $oldQty = $item->qty;
        
        $newAmount = $request->qty * $request->u_price;
        
        $item->update([
            'item_description' => $request->item_description,
            'unit' => $request->unit,
            'qty' => $request->qty,
            'weight' => $request->weight,
            'kg_per_item' => $request->kg_per_item,
            'u_price' => $request->u_price,
            'amount' => $newAmount,
        ]);

        // Update purchase totals
        $purchase = Purchase::findOrFail($purchaseId);
        $purchase->total_amount = $purchase->total_amount - $oldAmount + $newAmount;
        $purchase->qty = $purchase->qty - $oldQty + $request->qty;
        $purchase->save();

        return redirect()->route('purchase.items', $purchaseId)->with('success', 'Item berhasil diupdate.');
    }

    public function destroy($purchaseId, $itemId)
    {
        $item = Item::where('purchase_id', $purchaseId)->findOrFail($itemId);
        $oldAmount = $item->amount;
        $oldQty = $item->qty;
        
        $item->delete();

        // Update purchase totals
        $purchase = Purchase::findOrFail($purchaseId);
        $purchase->total_amount -= $oldAmount;
        $purchase->qty -= $oldQty;
        $purchase->save();

        return redirect()->route('purchase.items', $purchaseId)->with('success', 'Item berhasil dihapus.');
    }
}