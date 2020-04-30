<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Implementation extends Model
{
    protected $table = 'b2b_implementations';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_edit';
	protected $dateFormat = 'U';

	public function products(){
		return $this->hasMany('App\Models\Order\ImplementationProduct','implementation_id');
	}

	public function sender(){
		return $this->hasOne('App\User','id','sender_id');
	}

	public function customer(){
		return $this->hasOne('App\User','id','customer_id');
	}

}
