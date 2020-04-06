<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyPrice extends Model
{
	protected $table = 'b2b_company_prices';
	protected $dateFormat = 'U';

	protected $fillable = [
		'name', 'koef', 'company_id',
	];

	public function company(){
		return $this->hasOne('App\Models\Company\Company','id', 'company_id');
	}

}
