<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    use HasFactory;

     protected $casts = [
        'progress_date' => 'date', // Tambahkan ini
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $fillable = [
        'project_id',
        'progress_date',
        'type',
        'progress_value',
        'description',
        'user_id'
    ];
    
    public function project()
    {
        return $this->belongsTo(MasterProject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}