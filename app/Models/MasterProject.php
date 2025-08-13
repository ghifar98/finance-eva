<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
   use Illuminate\Support\Facades\DB;


class MasterProject extends Model
{
    use HasFactory;

    // ✅ Tambahkan baris ini agar Laravel tahu nama tabel sebenarnya
    protected $table = 'master_projects';

    protected $fillable = [
        'user_id',
        'project_name',
        'project_description',
        'tahun',
        'nilai',
        'kontrak',
        'vendor',
        'start_project',
        'end_project',
        'rab',
        'data_proyek',
        'progress'
    ];

    public function progressEntries()
    {
        return $this->hasMany(ProjectProgress::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

 
public function rab()
{
    return $this->hasMany(RAB::class, 'project_id');
}


    public function wbs()
    {
        return $this->hasMany(Wbs::class, 'project_id');
    }
    public function rabs()
{
    return $this->hasMany(RAB::class, 'project_id');
}
public function getWeeklyProgress($id)
{
    $data = DB::table('master_projects as mp')
        ->join('project_progress as pp', 'pp.project_id', '=', 'mp.id')
        ->select(
            'mp.id as project_id',
            'mp.project_name',
            DB::raw('FLOOR(DATEDIFF(pp.progress_date, mp.start_project) / 7) + 1 as minggu_ke'),
            'pp.progress_date',
            DB::raw('SUM(pp.progress_value) as total_progress_mingguan')
        )
        ->where('mp.id', $id) // ✅ Filter berdasarkan project_id
        ->whereBetween('pp.progress_date', [DB::raw('mp.start_project'), DB::raw('mp.end_project')])
        ->where('pp.type', 'weekly')
        ->groupBy('mp.id', 'minggu_ke', 'pp.progress_date')
        ->orderBy('mp.id')
        ->orderBy('minggu_ke')
        ->orderBy('pp.progress_date')
        ->get();

    return $data;
}
    // app/Models/MasterProject.php

public function eva()
{
    return $this->hasOne(Eva::class, 'project_id'); // BENAR
}
public function getRabWeekly()
{

    $data = DB::table('master_projects as mp')
        ->join('rab_weeklies as rw', 'rw.project_id', '=', 'mp.id')
        ->select(
            'mp.id as project_id',
            'mp.project_name',
            'rw.minggu',
            'rw.kategori',
            'rw.jumlah'
        )
        ->where('mp.id', $this->id) 
        ->orderBy('rw.minggu')
        ->get();
    return $this->hasMany(RabWeekly::class, 'project_id');
}

}