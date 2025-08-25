<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eva extends Model
{
    protected $fillable = [
        'project_id',
        'week_number',
        'report_date',
        'progress',
        'bac',
        'pv',
        'ev',
        'ac',
        'sv',
        'cv',
        'spi',
        'cpi',
        'eac',
        'vac',
        'notes',
        'status',
        'status_updated_at',
        'status_updated_by'
    ];

    protected $casts = [
        'report_date' => 'date',
        'status_updated_at' => 'datetime'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(MasterProject::class);
    }

    public function statusUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'green',
            'rejected' => 'red',
            'pending' => 'yellow',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'pending' => 'Menunggu Persetujuan',
            default => 'Tidak Diketahui'
        };
    }
}