<?php

namespace App\Http\Controllers;

use App\Models\Incomestatement;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Account;

class PurchaseController extends Controller
{
    public function show($id)
    {
        $purchase = Purchase::with(['vendor','project'])->findOrFail($id);
        $accounts = Account::pluck('name', 'id'); 
        // key = id (int, disimpan di DB), value = name (untuk ditampilkan)

        return view('purchase.show', compact('purchase', 'accounts'));
    }

    public function index(Request $request)
    {
        $query = Purchase::with('project');

        // Handle project filter
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('po_no', 'like', "%{$searchTerm}%")
                  ->orWhere('company', 'like', "%{$searchTerm}%")
                  ->orWhere('rep_name', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%")
                  ->orWhereHas('project', function ($projectQuery) use ($searchTerm) {
                      $projectQuery->where('project_name', 'like', "%{$searchTerm}%");
                    });
            });
        }
        
        return view('purchase.index', [
            'purchases' => $query->paginate(10),
            'projects' => \App\Models\MasterProject::all(),
        ]);
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
    $purchase = Purchase::create($data);
    
    // Redirect ke halaman items setelah purchase berhasil dibuat
    return redirect()->route('purchase.item.create', $purchase->id)->with('success', 'Purchase created successfully. You can now add items to this purchase.');
}
    public function updatestatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak,belum disetujui',
        ]);

        $purchase = Purchase::findOrFail($id);
        $purchase->status = $request->input('status');
        $purchase->account_id = $request->input('account_id');
        $purchase->save();

        if($purchase->status === 'disetujui') {
            Incomestatement::updateOrCreate([
                'purchase_id' => $id,
            ],[
                'item_description' => 'Pembelian Barang',
                'account_id' => $purchase->account_id,
                'type' => 'debit',
                'nominal' => $purchase->total_amount??0,
            ]);
        }

        // Check if redirect_to parameter exists
        $redirectTo = $request->input('redirect_to') === 'items' ? 'purchase.items' : 'purchase.show';
        return redirect()->route($redirectTo, ['id' => $id])->with('success', 'Purchase status updated successfully.');
    }

    // ========== NEW METHODS FOR ITEMS MANAGEMENT ==========

    /**
     * Show the items and status management page for the specified purchase.
     */
    public function items($id)
    {
        $purchase = Purchase::with(['vendor','project'])->findOrFail($id);
        $accounts = Account::pluck('name', 'id'); 
        
        return view('purchase.items', compact('purchase', 'accounts'));
    }

    /**
     * Show the form for creating a new item for the specified purchase.
     */
    public function createItem($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('purchase.item-create', compact('purchase'));
    }

    /**
     * Store a new item for the specified purchase.
     */
    public function storeItem(Request $request, $id)
    {
        $request->validate([
            'item_description' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'qty' => 'required|numeric|min:1',
            'weight' => 'nullable|numeric|min:0',
            'kg_per_item' => 'nullable|numeric|min:0',
            'u_price' => 'required|numeric|min:0',
        ]);

        $purchase = Purchase::findOrFail($id);
        
        // Calculate amount
        $amount = $request->qty * $request->u_price;
        
        \App\Models\Item::create([
            'purchase_id' => $id,
            'item_description' => $request->item_description,
            'unit' => $request->unit,
            'qty' => $request->qty,
            'weight' => $request->weight,
            'kg_per_item' => $request->kg_per_item,
            'u_price' => $request->u_price,
            'amount' => $amount,
        ]);

        return redirect()->route('purchase.items', $id)->with('success', 'Item added successfully.');
    }

    /**
     * Show the form for editing the specified item.
     */
    public function editItem($purchaseId, $itemId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $item = \App\Models\Item::where('purchase_id', $purchaseId)->findOrFail($itemId);
        
        return view('purchase.item-edit', compact('purchase', 'item'));
    }

    /**
     * Update the specified item.
     */
    public function updateItem(Request $request, $purchaseId, $itemId)
    {
        $request->validate([
            'item_description' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'qty' => 'required|numeric|min:1',
            'weight' => 'nullable|numeric|min:0',
            'kg_per_item' => 'nullable|numeric|min:0',
            'u_price' => 'required|numeric|min:0',
        ]);

        $item = \App\Models\Item::where('purchase_id', $purchaseId)->findOrFail($itemId);
        
        // Calculate amount
        $amount = $request->qty * $request->u_price;
        
        $item->update([
            'item_description' => $request->item_description,
            'unit' => $request->unit,
            'qty' => $request->qty,
            'weight' => $request->weight,
            'kg_per_item' => $request->kg_per_item,
            'u_price' => $request->u_price,
            'amount' => $amount,
        ]);

        return redirect()->route('purchase.items', $purchaseId)->with('success', 'Item updated successfully.');
    }

    /**
     * Delete the specified item.
     */
    public function deleteItem($purchaseId, $itemId)
    {
        $item = \App\Models\Item::where('purchase_id', $purchaseId)->findOrFail($itemId);
        $item->delete();

        return redirect()->route('purchase.items', $purchaseId)->with('success', 'Item deleted successfully.');
    }
}