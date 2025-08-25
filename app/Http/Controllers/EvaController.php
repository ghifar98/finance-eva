<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Eva;
use App\Models\MasterProject;
use App\Models\Incomestatement;
use App\Models\ProjectProgress;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EvaController extends Controller
{
   public function index(Request $request)
{
    $projects = MasterProject::all();
    $evas = Eva::query();
    
    if ($request->project_id) {
        $evas->where('project_id', $request->project_id);
    }
    
    $evas = $evas->with('project')->latest()->get();
    
    return view('eva.index', compact('evas', 'projects'));
}


    public function create(Request $request)
    {
        $projects = MasterProject::all();
        $weeklyProgress = [];
        $selectedProjectId = $request->input(key: 'project_id');
        $selectedWeek = $request->input('minggu_ke');
        $plannedValue = null;

        $project = MasterProject::find($selectedProjectId);
        
        
        if ($selectedProjectId) {
            $startDate = Carbon::parse($project->start_project);
            $project = MasterProject::find($selectedProjectId);

            if ($project) {
                $startDate = Carbon::parse($project->start_project);

                // Ambil progress mingguan dan kelompokkan berdasarkan minggu ke-n
                $weeklyProgress = ProjectProgress::where('project_id', $selectedProjectId)
                    ->where('type', 'weekly')
                    ->orderBy('progress_date')
                    ->get()
                    ->groupBy(function ($item) use ($startDate) {
                        return floor($startDate->diffInDays(Carbon::parse($item->progress_date)) / 7) + 1;
                    });
                     if ($selectedWeek) {
            $plannedValue = $this->calculatePlannedValue($selectedProjectId, $selectedWeek);
        }
    
            }
        }

        return view('eva.create', compact('projects', 'weeklyProgress', 'selectedProjectId', 'plannedValue', 'selectedWeek'));
    }
public function store(Request $request)
{
    $validated = $this->validateRequest($request);

    $project = MasterProject::findOrFail($validated['project_id']);
    $weekDates = $this->getWeekDates($project, $validated['minggu_ke']);
    [$startDate, $endDate] = [$weekDates[0], end($weekDates)];

    $filteredProgress = $this->getFilteredProgress($project->id, $validated['minggu_ke']);
    $progressValue = $filteredProgress->first()->total_progress_mingguan ?? 0;
    $reportDate = $filteredProgress->first()->progress_date ?? now();
    $ac = $this->calculateActualCost($project->id, $startDate, $endDate);
    $bac = $project->nilai;
 $ev = round((($progressValue / 100) * $bac) / 25000) * 25000;
    // dd($ac,$progressValue,      $validated['minggu_ke']);




    // dd($ev,$progressValue, $bac , $progressValue / 100, $bac / 1000, $ev / 1000);


    // ✅ Ini PV dari rab_weeklies:
    $pv = $this->calculatePlannedValue($project->id, $validated['minggu_ke']);

    // EVA Metrics
    $sv = $ev - $pv;
    $cv = $ev - $ac;
    $spi = $pv > 0 ? $ev / $pv : 0;
    $cpi = $ac > 0 ? $ev / $ac : 0;
    $eac = $cpi > 0 ? $bac / $cpi : 0;
    $vac = $bac - $eac;

    Eva::updateOrCreate(
        ['project_id' => $project->id, 'week_number' => $validated['minggu_ke']],
        [
            'project_id'   => $project->id,
            'week_number'  => $validated['minggu_ke'],
            'report_date'  => $reportDate,
            'progress'     => $progressValue,
            'bac'          => $bac,
            'pv'           => $pv,
            'ev'           => $ev,
            'ac'           => $ac,
            'sv'           => round($sv, 2),
            'cv'           => round($cv, 2),
            'spi'          => round($spi, 2),
            'cpi'          => round($cpi, 2),
            'eac'          => round($eac, 2),
            'vac'          => round($vac, 2),
            'notes'        => $validated['notes'] ?? null,
        ]
    );

    return redirect()->route('eva.index')->with('success', 'EVA berhasil disimpan');
}



public function show(Eva $eva)
{
    $eva->load('project');
    return view('eva.show', compact('eva'));
}




    /**
     * 
     */
private function calculatePlannedValue($projectId, $weekNumber)
{
    return DB::table('rab_weeklies')
        ->where('project_id', $projectId)
        ->where('minggu', $weekNumber)
        ->sum('jumlah');
}

 private function validateRequest(Request $request)
{
    return $request->validate([
        'project_id' => 'required|exists:master_projects,id',
        'minggu_ke' => 'required|numeric|min:1',
        'pv' => 'required|numeric|min:0', // ✅ Tidak pakai max:100
        'notes' => 'nullable|string',
    ]);
}


private function getWeekDates($project, $weekNumber)
{
    $start = Carbon::parse($project->start_project);
    $end = Carbon::parse($project->end_project);
    $weeklyGroups = [];
    $current = $start->copy();
    $week = 1;

    while ($current->lte($end)) {
        $weekDates = [];

        for ($i = 0; $i < 7 && $current->lte($end); $i++) {
            $weekDates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        $weeklyGroups[$week] = $weekDates;
        $week++;
    }

    return $weeklyGroups[$weekNumber] ?? [];
}

private function getFilteredProgress($projectId, $weekNumber)
{
    $progress = (new MasterProject())->getWeeklyProgress($projectId, 1);
    return $progress->filter(fn($item) => $item->minggu_ke == $weekNumber);
}

private function calculateActualCost($projectId, $startDate, $endDate)
{
    $accounts = Account::with(['incomestatements' => function ($query) use ($startDate, $endDate, $projectId) {
        $query->whereHas('purchase', function ($subQ) use ($startDate, $endDate, $projectId) {
            $subQ->whereBetween('date', [$startDate, $endDate])
                ->where('project_id', $projectId);
        });
    }])
    ->whereIn('code', ['50000'])
    ->get();

    $total = 0;

    foreach ($accounts as $account) {
        $total += $this->sumAccountNominal($account, $startDate, $endDate, $projectId);

        // Cek akun induk dan akumulasi anak-anaknya
        $prefix = $this->getCodePrefix($account->code);

        if ($prefix) {
            $childAccounts = Account::where('code', 'like', "$prefix%")
                ->where('code', '!=', $account->code)
                ->with(['incomestatements.purchase'])
                ->get();

            foreach ($childAccounts as $child) {
                $total += $this->sumAccountNominal($child, $startDate, $endDate, $projectId);
            }
        }
    }

    return $total;
}


private function sumAccountNominal($account, $startDate, $endDate, $projectId)
{
    return $account->incomestatements
        ->filter(function ($entry) use ($startDate, $endDate, $projectId) {
            $entryDate = optional($entry->purchase)->date;
            return $entryDate && $entryDate >= $startDate && $entryDate <= $endDate &&
                optional($entry->purchase)->project_id == $projectId;
        })
        ->sum('nominal');
}

private function getCodePrefix($code)
{
    if (strlen($code) === 5 && substr($code, -4) === '0000') {
        return substr($code, 0, 1);
    } elseif (strlen($code) === 5 && substr($code, -3) === '000') {
        return substr($code, 0, 2);
    }
    return null;
}
 public function updateNotes(Request $request, Eva $eva)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $eva->notes = $request->notes;
        $eva->save();

        return redirect()->back()->with('success', 'Catatan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Eva $eva)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'reason' => 'nullable|string|max:500'
        ]);

        $eva->update([
            'status' => $request->status,
            'status_updated_at' => now(),
            'status_updated_by' => Auth::id()
        ]);

        // Jika ada alasan penolakan, tambahkan ke catatan
        if ($request->status === 'rejected' && $request->reason) {
            $currentNotes = $eva->notes ?? '';
            $rejectionNote = "\n\n--- STATUS DITOLAK ---\n" . 
                           "Tanggal: " . now()->format('d/m/Y H:i') . "\n" .
                           "Oleh: " . Auth::user()->name . "\n" .
                           "Alasan: " . $request->reason;
            
            $eva->update([
                'notes' => $currentNotes . $rejectionNote
            ]);
        }

        $statusText = match($request->status) {
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'pending' => 'dikembalikan ke status menunggu'
        };

        return redirect()->back()->with('success', "EVA berhasil {$statusText}.");
    }
}
