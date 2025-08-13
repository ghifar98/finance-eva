<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RAB extends Model
{
    use HasFactory;
  protected $table = 'rabs';
    protected $fillable = [
        'project_id', 'desc', 'unit', 'qty', 'mat_supply', 'unit_price',
        'amount', 'total_bef_tax', 'total_bef_ppn', 'ppn', 'total_after_tax'
    ];

    public function project()
    {
        return $this->belongsTo(MasterProject::class, 'project_id');
    }
}
