<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
	protected $table = 'b2b_company_documents';
	protected $dateFormat = 'U';

	protected $fillable = [
		'name', 'info', 'folder', 'document', 'manager_add', 'company_id', 'manager_edit'
	];

	public function company(){
		return $this->hasOne('App\Models\Company\Company','id', 'company_id');
	}
}
