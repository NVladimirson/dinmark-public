<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $table = 's_shopshowcase_products';
	public $timestamps = false;

	public function content(){
		return $this->hasMany('App\Models\Content','content');
	}
}
