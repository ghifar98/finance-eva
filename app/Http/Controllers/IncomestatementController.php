<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'project_id' => 'nullable|exists:master_projects,id',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $projectIdSelected = $request->input('project_id');

        $query = Purchase::query();

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $projects= \App\Models\MasterProject::select('id', 'project_name')->get();

        $amount=0;
        if($startDate && $endDate && $projectIdSelected){
            $query->where('project_id', $projectIdSelected);
            $amount = $query->sum('total_amount');
        } 
        return view('incomestatement.index', compact('startDate', 'endDate', 'amount','projects', 'projectIdSelected'));
    }
}
