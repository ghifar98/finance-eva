<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RAB;
use App\Models\MasterProject;

class RABController extends Controller
{
   public function index(Request $request)
{
    $projects = MasterProject::all(); // untuk dropdown

    $selectedProject = null;
    $rabs = collect();
    $total = 0;

    if ($request->filled('project_id')) {
        $selectedProject = MasterProject::with('rabs')->find($request->project_id);

        if ($selectedProject) {
            $rabs = $selectedProject->rabs;
            $total = $rabs->sum('total_after_tax');
        }
    }

    return view('rab.index', compact('projects', 'selectedProject', 'rabs', 'total'));
}


    public function create()
    {
        // Menampilkan proyek yang belum memiliki RAB
        $projects = MasterProject::doesntHave('rab')->get();
        return view('rab.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:master_projects,id',
            'desc' => 'required|array',
            'desc.*' => 'required|string',
            'unit' => 'nullable|array',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric',
            'mat_supply' => 'nullable|array',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric',
        ]);

        $project_id = $request->project_id;

        foreach ($request->desc as $index => $desc) {
            $unit = $request->unit[$index] ?? null;
            $qty = $request->qty[$index] ?? 0;
            $mat_supply = $request->mat_supply[$index] ?? null;
            $unit_price = $request->unit_price[$index] ?? 0;

            $amount = $qty * $unit_price;
            $ppn = $amount * 0.11;
            $total_after_tax = $amount + $ppn;

            RAB::create([
                'project_id' => $project_id,
                'desc' => $desc,
                'unit' => $unit,
                'qty' => $qty,
                'mat_supply' => $mat_supply,
                'unit_price' => $unit_price,
                'amount' => $amount,
                'total_bef_tax' => $amount,
                'total_bef_ppn' => $amount,
                'ppn' => $ppn,
                'total_after_tax' => $total_after_tax,
            ]);
        }

        return redirect()->route('rab.index')->with('success', 'RAB berhasil disimpan.');
    }
}
