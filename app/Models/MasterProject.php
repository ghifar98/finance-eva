<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProject extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'progress' // Tambahkan ini
    ];

    // Tambahkan relasi ke progressEntries
    public function progressEntries()
    {
        return $this->hasMany(ProjectProgress::class, 'project_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}