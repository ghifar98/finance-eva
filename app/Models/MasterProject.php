<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MasterProject extends Model
{
    use HasFactory;

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
        'progress',
        // Field baru yang sudah ada di database
        'kode_project',
        'lokasi_proyek',
        'nama_klien',
        'perusahaan_klien',
        'nama_kontraktor',
        'jenis_pekerjaan',
        'nomor_spk',
        'durasi_proyek',
        'nilai_project',
        'ppn_percentage',
        'ppn_amount',
        'total_nilai_kontrak',
        'termin_pembayaran',
        'invoice_ke',
        'periode_pekerjaan'
    ];

    protected $casts = [
        'start_project' => 'date',
        'end_project' => 'date',
        'nilai' => 'integer',
        'nilai_project' => 'decimal:2',
        'ppn_percentage' => 'decimal:2',
        'ppn_amount' => 'decimal:2',
        'total_nilai_kontrak' => 'decimal:2',
        'progress' => 'decimal:2',
        'durasi_proyek' => 'integer'
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
            ->where('mp.id', $id)
            ->whereBetween('pp.progress_date', [DB::raw('mp.start_project'), DB::raw('mp.end_project')])
            ->where('pp.type', 'weekly')
            ->groupBy('mp.id', 'minggu_ke', 'pp.progress_date')
            ->orderBy('mp.id')
            ->orderBy('minggu_ke')
            ->orderBy('pp.progress_date')
            ->get();

        return $data;
    }

    public function eva()
    {
        return $this->hasOne(Eva::class, 'project_id');
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

    // Method untuk menghitung PPN otomatis
    public function calculatePPN()
    {
        if ($this->nilai_project && $this->ppn_percentage) {
            $this->ppn_amount = ($this->nilai_project * $this->ppn_percentage) / 100;
            $this->total_nilai_kontrak = $this->nilai_project + $this->ppn_amount;
        }
    }

    // Method untuk menghitung durasi proyek otomatis
    public function calculateDuration()
    {
        if ($this->start_project && $this->end_project) {
            $start = \Carbon\Carbon::parse($this->start_project);
            $end = \Carbon\Carbon::parse($this->end_project);
            $this->durasi_proyek = $start->diffInDays($end) + 1;
        }
    }
}