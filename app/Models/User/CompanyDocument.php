<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
	protected $table = 'b2b_company_documents';

	public function company(){
		return $this->hasOne('App\Models\User\Company','id', 'company_id');
	}
}
