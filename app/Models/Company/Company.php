<?php

namespace App\Models\Company;

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

	public function documents(){
		return $this->hasMany('App\Models\Company\CompanyDocument','company_id');
	}

	public function users(){
		return $this->hasMany('App\User','company');
	}

	public function type_prices(){
		return $this->hasMany('App\Models\Company\CompanyPrice','company_id');
	}
}
