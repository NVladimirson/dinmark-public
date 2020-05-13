<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
	protected $table = 's_library_articles';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_edit';
	protected $dateFormat = 'U';

	public function content(){
		return $this->hasMany('App\Models\Content','content');
	}
}
