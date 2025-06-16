<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function create($id)
    {
        $purchase = \App\Models\Purchase::findOrFail($id);

        return view('item.create')->with('purchase', $purchase);
    }
    
    public function store(Request $request,$id)
    {
        $request->validate([
            'item_description' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'qty' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'kg_per_item' => 'nullable|numeric|min:0',
            'u_price' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
        ], [
            'item_description.required' => 'Deskripsi item wajib diisi.',
            'unit.required' => 'Satuan harus diisi.',
            'qty.required' => 'Jumlah harus diisi.',
            'u_price.required' => 'Harga satuan harus diisi.',
            'amount.required' => 'Total harus diisi.',
        ]);

        $data = $request->all();
        $data['purchase_id'] = $id; // Assuming you want to associate this item with a purchase
        $data['amount'] = $data['qty'] * $data['u_price']; // Calculate amount if not provided
        Item::create($data);

        $purchase = \App\Models\Purchase::findOrFail($id);
        $purchase->total_amount += $data['amount']; // Update total amount in purchase
        $purchase->qty += $data['qty']; // Update total quantity in purchase
        $purchase->save();
       

        return redirect()->route('purchase.show',['id'=>$id])->with('success', 'Item berhasil ditambahkan.');
    }
}
