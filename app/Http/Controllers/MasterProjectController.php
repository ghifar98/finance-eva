<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterProject;
use App\Models\ProjectProgress;

class MasterProjectController extends Controller
{
   public function index()
{
    $activeProjects = MasterProject::with('user')
        ->where('progress', '<', 100)
        ->orderBy('created_at', 'desc')
        ->get();

    $completedProjects = MasterProject::with('user')
        ->where('progress', '>=', 100)
        ->orderBy('updated_at', 'desc')
        ->get();

    return view('master-project.index', [
        'activeProjects' => $activeProjects,
        'completedProjects' => $completedProjects,
        'totalProjects' => MasterProject::count(),
        'activeProjectsCount' => $activeProjects->count(),
        'completedProjectsCount' => $completedProjects->count()
    ]);
}
    
    public function create(){
        return view('master-project/create');
    }
    
    public function getProjects(){
        $projects = MasterProject::with('user')->get();
        return response()->json($projects);
    }
    
    public function editProject($id){
        return view('master-project/edit', ['id' => $id]);
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
        $data['user_id'] = Auth::id();
        $data['progress'] = 0; // Default progress 0%

        MasterProject::create($data);

        return redirect()->route('master-projects.index')->with('success', 'Project created successfully.');
    }

   public function show($id)
{
    $project = MasterProject::with(['progressEntries' => function($query) {
        $query->orderBy('progress_date', 'desc');
    }, 'user'])->findOrFail($id);
    
    return view('master-project.show', compact('project'));
}

    public function edit($id){
        $project = MasterProject::findOrFail($id);
        return view('master-project/edit', compact('project'));
    }
    
    public function update(Request $request, $id){
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

        $project = MasterProject::findOrFail($id);
        $project->update($request->all());

        return redirect()->route('master-projects.index')->with('success', 'Project updated successfully.');
    }
    
    public function destroy($id){
        $project = MasterProject::findOrFail($id);
        
        // Hapus semua progress terkait
        $project->progressEntries()->delete();
        
        $project->delete();

        return redirect()->route('master-projects.index')->with('success', 'Project deleted successfully.');
    }
    
    // Tambahkan method untuk menyimpan progress
    public function storeProgress(Request $request, $projectId)
    {
        $request->validate([
            'progress_date' => 'required|date',
            'type' => 'required|in:daily,weekly,milestone',
            'progress_value' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $project = MasterProject::findOrFail($projectId);

        // Simpan progres baru
        $progress = new ProjectProgress();
        $progress->project_id = $project->id;
        $progress->progress_date = $request->progress_date;
        $progress->type = $request->type;
        $progress->progress_value = $request->progress_value;
        $progress->description = $request->description;
        $progress->user_id = Auth::id();
        $progress->save();

        // Update progres terbaru di master project
        $project->progress = $request->progress_value;
        $project->save();

        return redirect()->back()
                         ->with('success', 'Progres berhasil disimpan!');
    }
}