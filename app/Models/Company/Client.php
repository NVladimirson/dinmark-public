<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	protected $table = 'b2b_clients';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_update';
	protected $dateFormat = 'U';

	protected $fillable  = [
			'name',
			'company_name',
			'company_edrpo',
			'email',
			'phone',
			'address',
			'company_id',
		];

	public function company(){
		return $this->hasOne('App\Models\Company\Company','id', 'company_id');
	}
}
