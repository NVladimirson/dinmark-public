<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class ImplementationProduct extends Model
{
	protected $table = 'b2b_implementation_products';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_edit';
	protected $dateFormat = 'U';

	public function orderProduct(){
		return $this->hasOne('App\Models\Order\OrderProduct','id','order_product_id');
	}

	public function implementation(){
		return $this->hasOne('App\Models\Order\Implementation','id','implementation_id');
	}

	public function reclamationProduct(){
			return $this->hasOne('App\Models\Reclamation\ReclamationProduct','implementation_product_id','id');
	}
}
