<?php

namespace App\Models\Reclamation;

use Illuminate\Database\Eloquent\Model;

class ReclamationProduct extends Model
{
    protected $table = 'b2b_reclamation_products';
    const CREATED_AT = 'date_add';
    const UPDATED_AT = 'date_update';
    protected $dateFormat = 'U';

    protected $fillable = [
        'implementation_product_id',
        'quantity',
        'note',
        'status',
        'reclamation_id',
    ];

    public function reclamation(){
        return $this->hasOne('App\Models\Reclamation\Reclamation','id','reclamation_id');
    }

    public function product(){
        return $this->hasOne('App\Models\Order\ImplementationProduct','id','implementation_product_id');
    }
}
