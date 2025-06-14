<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterProject;

class MasterProjectController extends Controller
{
    public function index(){
        return view('master-project/index');
        
    }
    public function create(){
        return view('master-project/create');
    }
    public function store(Request $request){
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'nullable|string|max:1000',
            'tahun' => 'nullable|integer',
            'nilai' => 'nullable|numeric',
            'kontrak' => 'string|max:255',
            'vendor' => 'nullable|string|max:255',
            'start_project' => 'nullable|date',
            'end_project' => 'nullable|date',
            'rab' => 'nullable|string|max:255',
            'data_proyek' => 'nullable|file|mimes:pdf,jpg,png'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::user()->id; // Set the user_id to the authenticated user's ID

        //save to database
        MasterProject::create($data);
        

        return redirect()->route('master-projects.index')->with('success', 'Project created successfully.');
    }

    public function show($id){
        $project = MasterProject::findOrFail($id);
        return view('master-project/show', compact('project'));
    }
}
