<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
	protected $table = 's_cart_products';
	public $timestamps = false;

	protected $fillable = [
		'cart',
		'user',
		'active',
		'product_alias',
		'product_id',
		'product_options',
		'storage_alias',
		'storage_invoice',
		'price',
		'price_in',
		'quantity',
		'quantity_wont',
		'quantity_returned',
		'discount',
		'bonus',
		'date',
	];

	public function getCart(){
		return $this->hasOne('App\Models\Order\Order','id','cart');
	}

	public function product(){
		return $this->hasOne('App\Models\Product\Product','id','product_id');
	}

	public function storage(){
		return $this->hasOne('App\Models\Storage\Storage','id','storage_alias');
	}

	public function implementationProduct(){
		return $this->hasMany('App\Models\Order\ImplementationProduct','order_product_id','id');
	}

	// public function orderProduct(){
	// 	return $this->hasOne('App\Models\Order\OrderProduct','id','order_product_id');
	// }
	// public function product(){
	// 		return $this->hasOne('App\Models\Order\ImplementationProduct','id','implementation_product_id');
	// }
	public function reclamationProduct()
{
		return $this->hasOneThrough(
				'App\Models\Reclamation\ReclamationProduct',
				'App\Models\Order\ImplementationProduct',
				'order_product_id',
				'implementation_product_id',
				'id',
				'id'
		);
}

}
