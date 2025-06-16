<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incomestatement extends Model
{
    //
    protected $table = 'incomestatements';
   

    protected $fillable = [
        'purchase_id',
        'item_description',
        'type',
        'nominal',
        
    ];
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}

