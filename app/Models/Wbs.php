<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wbs extends Model
{
    protected $fillable = ['project_id', 'minggu', 'kode', 'deskripsi'];

    public function project()
    {
        return $this->belongsTo(MasterProject::class, 'project_id');
    }
}
