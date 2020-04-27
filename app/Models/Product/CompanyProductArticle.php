<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class CompanyProductArticle extends Model
{
	protected $table = 'b2b_company_product_articles';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_update';
	protected $dateFormat = 'U';

	protected $fillable = [
		'article', 'holding_id', 'product_id'
	];

	public function product(){
		$this->hasOne('App\Models\Product\Product','id', 'product_id');
	}
}
