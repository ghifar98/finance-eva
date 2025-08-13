<?php
namespace App\Http\Controllers;

use App\Models\RabWeekly;
use App\Models\MasterProject;
use Illuminate\Http\Request;

class RabWeeklyController extends Controller
{
   public function index(Request $request)
{
    $projects = MasterProject::all();

    $data = collect(); // default kosong
    $selectedProject = null;

    if ($request->filled('project_id')) {
        $selectedProject = MasterProject::find($request->project_id);

        if ($selectedProject) {
            $data = RabWeekly::with('project')
                ->where('project_id', $request->project_id)
                ->get()
                ->groupBy(fn($item) => $item->project->project_name);
        }
    }

    return view('rab-weekly.index', compact('projects', 'data', 'selectedProject'));
}


    public function create()
    {
        $projects = MasterProject::all();
        return view('rab-weekly.create', compact('projects'));
    }

  public function store(Request $request)
{
    $request->validate([
        'project_id' => 'required|exists:master_projects,id',
        'minggu' => 'required|array',
        'minggu.*' => 'required|string',
        'kategori' => 'required|array',
        'jumlah' => 'required|array',
    ]);

    foreach ($request->minggu as $index => $minggu) {
        if (!isset($request->kategori[$index]) || !isset($request->jumlah[$index])) {
            continue; // skip jika data tidak lengkap
        }

        foreach ($request->kategori[$index] as $subIndex => $kategori) {
            $jumlah = $request->jumlah[$index][$subIndex] ?? null;

            if ($kategori && is_numeric($jumlah)) {
                RabWeekly::create([
                    'project_id' => $request->project_id,
                    'minggu'     => $minggu,
                    'kategori'   => $kategori,
                    'jumlah'     => $jumlah,
                ]);
            }
        }
    }

    return redirect()->route('rab-weekly.index')->with('success', 'Data RAB per minggu berhasil disimpan.');
}

}
