<?php

namespace App\Http\Controllers;

use App\Models\MasterProject;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = [
            'recentProjects' => MasterProject::with('user')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get(),
            
            // Anda bisa menambahkan data lain yang dibutuhkan untuk dashboard
            'totalProjects' => MasterProject::count(),
            'activeProjects' => MasterProject::where('progress', '<', 100)->count(),
            'completedProjects' => MasterProject::where('progress', 100)->count(),
        ];
        
        return view('dashboard', $data);
    }
}