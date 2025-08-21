<?php

namespace App\Http\Controllers;

use App\Models\Eva;
use App\Models\MasterProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dasbor dengan data agregat EVA.
     */
    public function dashboard()
    {
        // 1. Mengambil data EVA terbaru untuk metrik utama
        $latestEva = Eva::with('project')->latest('report_date')->first();

        // 2. Menghitung jumlah total dan proyek yang sedang berjalan
        $totalProjects = MasterProject::count();
        $ongoingProjects = MasterProject::where('end_project', '>=', now())
            ->where('start_project', '<=', now())
            ->count();

        // 3. Mengambil 5 proyek terbaru
        $recentProjects = MasterProject::latest()->take(5)->get();

        // 4. Menyiapkan data untuk grafik EVA tahunan
        $currentYear = date('Y');

        // Mengambil data EVA bulanan untuk tahun ini dan mengelompokkannya
        $monthlyData = Eva::whereYear('report_date', $currentYear)
            ->select(
                DB::raw('MONTH(report_date) as month'),
                DB::raw('SUM(pv) as total_pv'),
                DB::raw('SUM(ev) as total_ev'),
                DB::raw('SUM(ac) as total_ac')
            )
            ->groupBy(DB::raw('MONTH(report_date)'))
            ->orderBy('month', 'asc')
            ->get()
            ->keyBy('month'); // Menggunakan 'month' sebagai key untuk akses mudah

        $labels = [];
        $pvData = [];
        $evData = [];
        $acData = [];

        // --- BLOK YANG DIPERBAIKI ---
        // Mengisi data untuk setiap bulan (Januari hingga Desember)
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create()->month($m)->format('M');

            // Cek jika data untuk bulan '$m' ada
            if (isset($monthlyData[$m])) {
                $pvData[] = $monthlyData[$m]->total_pv;
                $evData[] = $monthlyData[$m]->total_ev;
                $acData[] = $monthlyData[$m]->total_ac;
            } else {
                // Jika tidak ada, isi dengan 0
                $pvData[] = 0;
                $evData[] = 0;
                $acData[] = 0;
            }
        }
        // --- AKHIR BLOK YANG DIPERBAIKI ---

        $chartData = [
            'labels' => $labels,
            'pv' => $pvData,
            'ev' => $evData,
            'ac' => $acData,
        ];

        // Mengirim semua data yang telah diproses ke view 'dashboard'
        return view('dashboard', compact(
            'latestEva',
            'totalProjects',
            'ongoingProjects',
            'recentProjects',
            'chartData'
        ));
    }
}