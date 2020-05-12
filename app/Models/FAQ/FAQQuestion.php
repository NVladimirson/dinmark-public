<?php

namespace App\Models\FAQ;

use Illuminate\Database\Eloquent\Model;

class FAQQuestion extends Model
{
	protected $table = 's_faq_questions';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_edit';
	protected $dateFormat = 'U';

	public function content(){
		return $this->hasMany('App\Models\Content','content');
	}
}
