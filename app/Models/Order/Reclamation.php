<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
	protected $table = 'b2b_reclamations';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_edit';
	protected $dateFormat = 'U';

	protected $fillable = [
		'implementation_product_id',
		'quantity',
		'note',
		'ttn',
		'status',
		'author',
	];

	public function user(){
		return $this->hasOne('App\User','id','author');
	}

	public function product(){
		return $this->hasOne('App\Models\Order\ImplementationProduct','id','implementation_product_id');
	}
}
