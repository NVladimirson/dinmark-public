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


}
