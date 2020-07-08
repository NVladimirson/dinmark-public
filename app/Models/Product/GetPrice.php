<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class GetPrice extends Model
{
    protected $table = 'get_price';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'phone',
        'product',
        'amount',
        'comment',
        'date_add',
        'language',
        'new',
    ];
}
