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
		'sender_id',
		'customer_id',
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

	public function getStatus(){
		return $this->hasOne('App\Models\Order\OrderStatus','id','status');
	}

	public function products(){
		return $this->hasMany('App\Models\Order\OrderProduct','cart');
	}

	public function payments(){
		return $this->hasMany('App\Models\Order\Payment','cart_id');
	}

	public function sender(){
		return $this->hasOne('App\User','id', 'sender_id');
	}

	public function customer(){
		return $this->hasOne('App\User','id', 'customer_id');
	}
}
