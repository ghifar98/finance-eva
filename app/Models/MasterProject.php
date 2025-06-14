<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProject extends Model
{
  protected $table = 'master_projects';

    protected $fillable = [
        'user_id',
        'project_name',
        'project_description',
        'tahun',
        'nilai',
        'kontrak',
        'vendor',
        'start_project',
        'end_project',
        'rab',
        'data_proyek'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
