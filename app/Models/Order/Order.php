<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table = 's_cart';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_edit';
	protected $dateFormat = 'U';

	protected $fillable = [
		'public_number',
		'user',
		'status',
		'shipping_id',
		'shipping_info',
		'payment_alias',
		'payment_id',
		'total',
		'payed',
		'bonus',
		'discount',
		'comment',
		'manager_comment',
		'ttn',
		'manager',
		'source',
	];

	public function getUser(){
		return $this->hasOne('App\User','id','user');
	}

}