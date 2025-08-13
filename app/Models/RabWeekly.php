<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RabWeekly extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'minggu',
        'kategori',
        'jumlah',
    ];

    public function project()
    {
        return $this->belongsTo(MasterProject::class, 'project_id');
    }
}

