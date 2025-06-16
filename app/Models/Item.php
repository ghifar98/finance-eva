<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'purchase_id',
        'item_description',
        'unit',
        'qty',
        'weight',
        'kg_per_item',
        'u_price',
        'amount',
    ];
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
