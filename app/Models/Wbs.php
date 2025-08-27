<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wbs extends Model
{
    // Add 'rencana_progres' to the fillable array
    protected $fillable = [
        'project_id', 
        'minggu', 
        'kode', 
        'deskripsi', 
        'rencana_progres'  // This was missing!
    ];

    // Add casts to ensure proper data types
    protected $casts = [
        'rencana_progres' => 'float',
        'project_id' => 'integer'
    ];

    public function project()
    {
        return $this->belongsTo(MasterProject::class, 'project_id');
    }
}