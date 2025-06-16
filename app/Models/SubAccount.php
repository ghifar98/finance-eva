<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubAccount extends Model
{
    protected $table = 'sub_accounts';

    protected $fillable = [
        'account_id',
        'name',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
