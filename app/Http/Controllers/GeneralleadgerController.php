<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralleadgerController extends Controller
{
    
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
           
        ], [
            'start_date.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'end_date.date' => 'Tanggal akhir harus berupa tanggal yang valid.',
            
        ]);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        // Logic to display the general ledger
        return view('generalleadger.index', compact('startDate', 'endDate'));
    }

    public function show($id)
    {
        // Logic to display a specific general ledger entry
        return view('general_ledger.show', compact('id'));
    }

   


      
}
