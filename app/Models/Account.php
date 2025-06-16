<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'pos_laporan',
        'pos_saldo',
        'deskripsi',
        'credit',
        'debit'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);

    }
    public function subAccounts()
    {
        return $this->hasMany(SubAccount::class);
    }
}
