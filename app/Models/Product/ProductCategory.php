<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductCategory extends Model
{
	protected $table = 's_shopshowcase_groups';
	public $timestamps = false;

	public function children(){
		return $this->hasMany('App\Models\Product\ProductCategory','parent');
	}
}
