<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incomestatement extends Model
{
    protected $table = 'incomestatements';

    protected $fillable = [
        'purchase_id',
        'project_id',
        'item_description',
        'type',
        'nominal',
        'account_id',
        'credit_account_id',
        'debit_account_id',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function project()
    {
        return $this->belongsTo(MasterProject::class, 'project_id');
    }

    public function creditAccount()
    {
        return $this->belongsTo(Account::class, 'credit_account_id');
    }

    public function debitAccount()
    {
        return $this->belongsTo(Account::class, 'debit_account_id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
