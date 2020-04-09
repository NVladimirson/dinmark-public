<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
	protected $table = 's_shopshowcase_product_options';
	public $timestamps = false;

	public function translates(){
		return $this->hasMany('App\Models\Product\ProductOptionName','option', 'option');
	}

	public function val(){
		return $this->hasOne('App\Models\Product\ProductOptionName','option', 'value');
	}

	public function option_val(){
		return $this->hasOne('App\Models\Product\ProductOptionValue','id', 'value');
	}
}
