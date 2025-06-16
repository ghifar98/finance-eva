<?php

namespace App\Http\Controllers;

use App\Models\Incomestatement;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function show($id)
{
    $purchase = Purchase::with(['vendor','project'])->findOrFail($id);

    return view('purchase.show', compact('purchase'));
}
    public function index()
    {
        // Logic to display the list of purchases
        return view('purchase.index');
    }

    public function create()
    {
        $vendors = \App\Models\Vendor::select('id', 'name')->get();
        $projects = \App\Models\MasterProject::select('id', 'project_name')->get();
        // Logic to show the form for creating a new purchase
        return view('purchase.create', compact('vendors', 'projects'));
        

    }

    public function store(Request $request)
    {
        //    'date',
        // 'po_no',
        // 'company',
        // 'project_id',
        // 'vendor_id',
        // 'package',
        // 'rep_name',
        // 'phone',
        // 'total_amount',
        // 'qty',
        // 'balance',
        // 'total_ppn',
    
        // Logic to store a new purchase
        // dd($request->all());
        $request->validate([
           
            'date' => 'required|date',
            'company' => 'required|string|max:255',
            'project_id' => 'required|exists:master_projects,id',
            'vendor_id' => 'required|exists:vendors,id',
            'package' => 'nullable|string|max:255',
            'rep_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
       
            
        ]);

        
        // Code to save the purchase in the database
        $data = $request->all();

        //make generator po number from id
        $data['po_no'] = 'PO-' . str_pad(Purchase::count() + 1, 5, '0', STR_PAD_LEFT);
        Purchase::create($data);
        return redirect()->route('purchase.index')->with('success', 'Purchase created successfully.');
    }
    public function updatestatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum disetujui,disetujui,ditolak', // Adjust the statuses as needed
        ]);
        $purchase = Purchase::findOrFail($id);
        $purchase->status = $request->input('status');
        $purchase->save();

        // Logic to update the status of a purchase
        //    'purchase_id',
        // 'item_description',
        // 'type',
        // 'nominal',
        
        if($purchase->status === 'disetujui') {
            Incomestatement::updateOrCreate([
                'purchase_id' => $id,
            ],[
                'item_description' => 'Pembelian Barang',
                'type' => 'debit',
                'nominal' => $purchase->total_amount??0,
            ]);
        }

        return redirect()->route('purchase.show', ['id' => $id])->with('success', 'Purchase status updated successfully.');
    }
}
