<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterProject;
use App\Models\ProjectProgress;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
        $query->orderBy('progress_date', 'asc');
    }, 'user'])->findOrFail($id);

    $weekly_groups = [];
    $active_week_group = null;

    if ($project->start_project && $project->end_project) {
        $start = Carbon::parse($project->start_project);
        $end = Carbon::parse($project->end_project);
        $now = Carbon::now();

        $weekNumber = 1;
        $currentWeekStart = $start->copy(); // Mulai tepat dari start_date project

        while ($currentWeekStart->lte($end)) {
            $weekEnd = $currentWeekStart->copy()->addDays(6); // 7 hari termasuk hari pertama
            if ($weekEnd->gt($end)) {
                $weekEnd = $end->copy();
            }

            // Get progress entries for this week
            $weekProgress = $project->progressEntries
                ->filter(function($entry) use ($currentWeekStart, $weekEnd) {
                    $entryDate = Carbon::parse($entry->progress_date);
                    return $entryDate->between($currentWeekStart, $weekEnd);
                })
                ->sortBy('progress_date');

            $weekProgressSum = $weekProgress->sum('progress_value');

            $weekData = [
                'week' => $weekNumber,
                'start_date' => $currentWeekStart->format('Y-m-d'),
                'end_date' => $weekEnd->format('Y-m-d'),
                'progress' => $weekProgress,
                'total_progress' => min($weekProgressSum, 100),
                'max_progress' => min($weekProgressSum, 100),
            ];

            $weekly_groups[] = $weekData;

            // Check if current week is active
            if ($now->between($currentWeekStart, $weekEnd)) {
                $active_week_group = $weekData;
            }

            $currentWeekStart->addDays(7); // Geser 7 hari untuk minggu berikutnya
            $weekNumber++;
        }
    }
// dd((new MasterProject())->getWeeklyProgress($id));

    return view('master-project.show', compact('project', 'weekly_groups', 'active_week_group'));

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
       'progress_value' => [
    'required',
    function ($attribute, $value, $fail) {
        $normalized = str_replace(',', '.', $value);
        if (!is_numeric($normalized)) {
            $fail('Nilai progres tidak valid.');
        }
        if ((float)$normalized < 0 || (float)$normalized > 100) {
            $fail('Nilai progres harus antara 0 hingga 100.');
        }
    }
],

        'description' => 'nullable|string|max:500',
    ]);

    $project = MasterProject::findOrFail($projectId);

    // Get the current week's progress sum
    $progressDate = Carbon::parse($request->progress_date);
    $weekStart = $progressDate->copy()->startOfWeek();
    $weekEnd = $progressDate->copy()->endOfWeek();

    $weekProgressSum = $project->progressEntries()
        ->whereBetween('progress_date', [$weekStart, $weekEnd])
        ->sum('progress_value');

    // Check if adding new progress would exceed 100%
    if (($weekProgressSum + $request->progress_value) > 100) {
        return redirect()->back()
            ->withErrors(['progress_value' => 'Total progress for this week cannot exceed 100%'])
            ->withInput();
    }

    // Save new progress entry
    $progress = new ProjectProgress();
    $progress->project_id = $project->id;
    $progress->progress_date = $request->progress_date;
    $progress->type = $request->type;
    $progress->progress_value = $request->progress_value;
    $progress->description = $request->description;
    $progress->user_id = Auth::id();
    $progress->save();

    // Update project's overall progress (use max value)
    $maxProgress = $project->progressEntries()->max('progress_value');
    $project->progress = $maxProgress ?? $request->progress_value;
    $project->save();

    return redirect()->back()
        ->with('success', 'Progres berhasil disimpan!');
}
}