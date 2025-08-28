<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'progress_date', 
        'type',
        'progress_value',
        'description',
        'wbs_code',  // Tambahkan ini
        'user_id'
    ];

    protected $casts = [
        'progress_date' => 'date'
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(MasterProject::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}