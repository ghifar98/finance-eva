<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        
         'name',
        'address',
        'phone',
        'email',
        'contact_person',
        'bank_account',
        'tax_id',
        'description',
        'attn',
        'quote_ref',
        'subject',
        'gsm_no',
    ];

   



    
    public function projects()
    {
        return $this->hasMany(MasterProject::class, 'vendor', 'id');
    }
}
