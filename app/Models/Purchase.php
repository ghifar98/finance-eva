<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';

    protected $fillable = [
        'date',
        'po_no',
        'company',
        'project_id',
        'vendor_id',
        'status',
        'package',
        'rep_name',
        'phone',
        'total_amount',
        'qty',
        'balance',
        'total_ppn',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function project()
    {
        return $this->belongsTo(MasterProject::class, 'project_id');
    }

    
}
