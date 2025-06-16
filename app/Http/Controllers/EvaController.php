<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EVAController extends Controller
{
    public function index()
    {
        return view('eva.index');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'bac' => 'required|string',
            'ac' => 'required|string',
            'progress' => 'required|numeric|min:0|max:100',
        ]);

        // Bersihkan input dari Rp dan titik
        $bac = (int) str_replace(['Rp', '.', ' '], '', $request->bac);
        $ac = (int) str_replace(['Rp', '.', ' '], '', $request->ac);
        $progress = (float) $request->progress;

        $ev = $bac * ($progress / 100);
        $pv = $ev; // Jika asumsi progress sesuai jadwal
        $cpi = $ac != 0 ? $ev / $ac : 0;
        $spi = $pv != 0 ? $ev / $pv : 0;
        $cv = $ev - $ac;
        $sv = $ev - $pv;
        $eac = $cpi != 0 ? $bac / $cpi : 0;
        // Tambahkan setelah EAC dihitung
         $etc = $eac - $ac;
        $vac = $bac - $eac;
        // Jika EAC tidak valid, set ke 0
// Kirim ke view



        return view('eva.result', compact('bac', 'ac', 'progress', 'ev', 'pv', 'cpi', 'spi', 'cv', 'sv', 'eac','etc', 'vac'));
    }
}
