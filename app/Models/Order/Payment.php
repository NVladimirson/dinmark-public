<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $table = 'b2b_payments';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_edit';
	protected $dateFormat = 'U';

	public function order(){
		return $this->hasOne('App\Models\Order\Order','id', 'cart_id');
	}
}
