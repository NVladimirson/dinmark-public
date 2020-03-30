<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $table = 'companies';
	public $timestamps = false;

	public function getManager(){
		return $this->hasOne('App\User','id', 'manager');
	}

	public function getPrice(){
		return $this->hasOne('App\Models\User\UserPrice','user_type', 'price_type');
	}
}
