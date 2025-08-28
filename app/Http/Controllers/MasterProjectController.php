<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterProject;
use App\Models\ProjectProgress;
use Carbon\Carbon;
use App\Models\Wbs;
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

    public function create()
    {
        return view('master-project.create');
    }
    public function getProjects(){
        $projects = MasterProject::with('user')->get();
        return response()->json($projects);
    }
    
    public function editProject($id){
        return view('master-project/edit', ['id' => $id]);
    }
    
    
    public function store(Request $request)
    {
        $request->validate([
            'kode_project' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'tahun' => 'nullable|integer',
            'kontrak' => 'nullable|string|max:255',
            'nilai' => 'nullable|string',
            'start_project' => 'nullable|date',
            'end_project' => 'nullable|date',
            'asal_kode' => 'nullable|string|max:255',
            'data_proyek' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['progress'] = 0;

        // Convert nilai ke angka murni
        if ($request->filled('nilai')) {
            $data['nilai'] = preg_replace('/[^\d]/', '', $request->nilai);
        }

        // Upload dokumen
        if ($request->hasFile('data_proyek')) {
            $path = $request->file('data_proyek')->store('projects', 'public');
            $data['data_proyek'] = $path;
        }

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

    // Get available WBS codes that haven't been used yet
    $completedWbsCodes = ProjectProgress::where('project_id', $id)
        ->whereNotNull('wbs_code')
        ->pluck('wbs_code')
        ->toArray();

    $availableWbsCodes = Wbs::where('project_id', $id)
        ->whereNotIn('kode', $completedWbsCodes)
        ->orderBy('minggu')
        ->orderBy('kode')
        ->get();

    return view('master-project.show', compact('project', 'weekly_groups', 'active_week_group', 'availableWbsCodes'));
}
    public function edit($id){
        $project = MasterProject::findOrFail($id);
        return view('master-project/edit', compact('project'));
    }
    
public function update(Request $request, $id)
    {
        $request->validate([
            'kode_project' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'tahun' => 'nullable|integer',
            'kontrak' => 'nullable|string|max:255',
            'nilai' => 'nullable|string',
            'start_project' => 'nullable|date',
            'end_project' => 'nullable|date',
            'asal_kode' => 'nullable|string|max:255',
            'data_proyek' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $project = MasterProject::findOrFail($id);
        $data = $request->all();

        if ($request->filled('nilai')) {
            $data['nilai'] = preg_replace('/[^\d]/', '', $request->nilai);
        }

        if ($request->hasFile('data_proyek')) {
            $path = $request->file('data_proyek')->store('projects', 'public');
            $data['data_proyek'] = $path;
        }

        $project->update($data);

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
        'type' => 'required|in:daily,weekly,milestone,wbs',

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
public function downloadDocument($id)
{
    $project = MasterProject::findOrFail($id);
    
    if (!$project->data_proyek) {
        abort(404, 'Dokumen tidak ditemukan');
    }
    
    $path = public_path('storage/' . $project->data_proyek);
    
    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan');
    }
    
    $filename = $project->project_name . '_' . basename($path);
    
    return response()->download($path, $filename, [
        'Content-Type' => mime_content_type($path),
    ]);
}
public function streamDocument($id)
{
    $project = MasterProject::findOrFail($id);
    
    if (!$project->data_proyek) {
        abort(404, 'Dokumen tidak ditemukan');
    }
    
    $path = public_path('storage/' . $project->data_proyek);
    
    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan');
    }
    
    $fileSize = filesize($path);
    $mimeType = mime_content_type($path);
    
    return response()->stream(function () use ($path) {
        $handle = fopen($path, 'rb');
        while (!feof($handle)) {
            echo fread($handle, 1024 * 8); // Read 8KB chunks
            flush();
        }
        fclose($handle);
    }, 200, [
        'Content-Type' => $mimeType,
        'Content-Length' => $fileSize,
        'Cache-Control' => 'public, max-age=3600',
    ]);
}
  public function getWbsProgressSummary($projectId)
{
    $project = MasterProject::findOrFail($projectId);
    
    // Get all WBS items for this project
    $allWbsItems = Wbs::where('project_id', $projectId)
        ->select('id', 'kode', 'minggu', 'rencana_progres', 'deskripsi')
        ->orderBy('minggu')
        ->orderBy('kode')
        ->get();
    
    // Get completed WBS items berdasarkan wbs_code di project_progress
    $completedWbsCodes = ProjectProgress::where('project_id', $projectId)
        ->whereNotNull('wbs_code')
        ->pluck('wbs_code')
        ->toArray();
    
    // Calculate summary
    $totalWbsItems = $allWbsItems->count();
    $completedCount = count($completedWbsCodes);
    $remainingCount = $totalWbsItems - $completedCount;
    
    $totalPlannedProgress = $allWbsItems->sum('rencana_progres');
    $completedProgress = $allWbsItems->whereIn('kode', $completedWbsCodes)->sum('rencana_progres');
    $remainingProgress = $totalPlannedProgress - $completedProgress;
    
    return [
        'total_wbs_items' => $totalWbsItems,
        'completed_items' => $completedCount,
        'remaining_items' => $remainingCount,
        'total_planned_progress' => $totalPlannedProgress,
        'completed_progress' => $completedProgress,
        'remaining_progress' => $remainingProgress,
        'completion_percentage' => $totalWbsItems > 0 ? round(($completedCount / $totalWbsItems) * 100, 2) : 0,
        'all_wbs_items' => $allWbsItems,
        'completed_wbs_codes' => $completedWbsCodes,
    ];
}

    /**
     * Show WBS progress dashboard
     */
    public function showWbsProgress($id)
    {
        $project = MasterProject::findOrFail($id);
        $wbsSummary = $this->getWbsProgressSummary($id);
        
        return view('master-project.wbs-progress', compact('project', 'wbsSummary'));
    }

public function storeProgressFromWbs(Request $request, $projectId)
{
    $request->validate([
        'wbs_code' => 'required|string|exists:wbs,kode',
        'progress_date' => 'required|date',
        'description' => 'nullable|string|max:500',
    ]);

    $project = MasterProject::findOrFail($projectId);

    $wbs = Wbs::where('project_id', $projectId)
        ->where('kode', $request->wbs_code)
        ->firstOrFail();

    // Cek apakah progress untuk WBS ini sudah ada
    $alreadyLogged = ProjectProgress::where('project_id', $projectId)
        ->where('wbs_code', $wbs->kode)
        ->exists();

    if ($alreadyLogged) {
        return redirect()->back()
            ->withErrors(['wbs_code' => 'Progress untuk WBS ini sudah dicatat sebelumnya.']);
    }

    $progress = new ProjectProgress();
    $progress->project_id = $project->id;
    $progress->wbs_code = $wbs->kode;
    $progress->progress_date = $request->progress_date;
    $progress->type = 'wbs';
    $progress->progress_value = $wbs->rencana_progres;
    $progress->description = $request->description ?? $wbs->deskripsi;
    $progress->user_id = Auth::id();
    $progress->save();

    // Update progress project
    $totalProgress = ProjectProgress::where('project_id', $project->id)->sum('progress_value');
    $project->progress = min($totalProgress, 100);
    $project->save();

    return redirect()->back()->with('success', 'Progress WBS berhasil disimpan!');
}

}