<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    
    public function index()
    {
        // Logic to display the list of vendors
        return view('vendor.index');
    }
public function show($id)
{
    $vendor = Vendor::findOrFail($id);
    return view('vendor.show', compact('vendor'));
}

    public function create()
    {
        
        // Logic to show the form for creating a new vendor
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        // Logic to store a new vendor in the database
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'string|max:20',
            'address' => 'string|max:500',
            'attn' => 'string|max:255', // Attention (orang yang dituju)
            'quote_ref' => 'string|max:255', // Referensi Penawaran
            'subject' => 'string|max:255', // Subjek penawaran
            'gsm_no' => 'string|max:20', // Nomor HP

            // Add other fields as necessary
        ]);
        // dd($data);
        Vendor::create($data);

    
        return redirect()->route('vendor.index')->with('success', 'Vendor created successfully.');
    }
}
