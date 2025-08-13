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

        return redirect()->route('purchase.show', ['id' => $id])->with('success', 'Purchase status updated successfully.');
    }
}
