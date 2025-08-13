<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MasterProject; // ← INI WAJIB ADA

class Eva extends Model
{
    protected $fillable = [
        'project_id',
        'week_number',
        'report_date',
        'progress',
        'bac',
        'ac',
        'ev',
        'pv',
        'spi',
        'cpi',
        'notes',
        'sv',
        'cv',
        'eac',
        'vac',
    ];

    public function project()
    {
        return $this->belongsTo(MasterProject::class, 'project_id');
    }
}
