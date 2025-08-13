<?php

namespace App\Http\Controllers;

use App\Models\Wbs;
use App\Models\MasterProject;
use Illuminate\Http\Request;

class WbsController extends Controller
{
    public function index(Request $request)
    {
        $projects = MasterProject::all();

        $groupedWbs = collect();

        if ($request->filled('project_id')) {
            $groupedWbs = Wbs::with('project')
                ->where('project_id', $request->project_id)
                ->get()
                ->groupBy(fn($item) => $item->minggu . '-' . $item->kode);
        }

        return view('wbs.index', compact('groupedWbs', 'projects'));
    }

    public function create()
    {
        $projects = MasterProject::all();
        return view('wbs.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:master_projects,id',
            'minggu.*' => 'required',
            'kode.*' => 'required',
            'deskripsi.*' => 'required',
        ]);

        for ($i = 0; $i < count($request->minggu); $i++) {
            Wbs::create([
                'project_id' => $request->project_id,
                'minggu' => $request->minggu[$i],
                'kode' => $request->kode[$i],
                'deskripsi' => $request->deskripsi[$i],
            ]);
        }

        return redirect()->route('wbs.index', ['project_id' => $request->project_id])
            ->with('success', 'Data WBS berhasil disimpan.');
    }

    public function storeByProject(Request $request, $projectId)
    {
        $request->validate([
            'items.*.minggu' => 'required',
            'items.*.kode' => 'required',
            'items.*.deskripsi' => 'required|array|min:1',
            'items.*.deskripsi.*' => 'required|string',
        ]);

        foreach ($request->items as $item) {
            foreach ($item['deskripsi'] as $desc) {
                Wbs::create([
                    'project_id' => $projectId,
                    'minggu' => $item['minggu'],
                    'kode' => $item['kode'],
                    'deskripsi' => $desc,
                ]);
            }
        }

        return redirect()->route('wbs.index', ['project_id' => $projectId])
            ->with('success', 'Data WBS berhasil disimpan.');
    }

    public function edit(Wbs $wbs)
    {
        $projects = MasterProject::all();
        return view('wbs.edit', compact('wbs', 'projects'));
    }

    public function update(Request $request, Wbs $wbs)
    {
        $request->validate([
            'project_id' => 'required|exists:master_projects,id',
            'minggu' => 'required',
            'kode' => 'required',
            'deskripsi' => 'required',
        ]);

        $wbs->update($request->only(['project_id', 'minggu', 'kode', 'deskripsi']));

        return redirect()->route('wbs.index', ['project_id' => $wbs->project_id])
            ->with('success', 'Data WBS berhasil diupdate.');
    }

    public function destroy(Wbs $wbs)
    {
        $projectId = $wbs->project_id;
        $wbs->delete();

        return redirect()->route('wbs.index', ['project_id' => $projectId])
            ->with('success', 'Data WBS berhasil dihapus.');
    }
}
