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
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'account_id');
    }
    public function incomestatements()
    {
        return $this->hasMany(Incomestatement::class, 'account_id');
    }
}
