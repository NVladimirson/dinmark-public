<?php

namespace App\Models\Wishlist;

use Illuminate\Database\Eloquent\Model;

class LikeGroup extends Model
{
	protected $table = 's_like_groups';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_update';
	protected $dateFormat = 'U';

	protected $fillable = [
		'name', 'is_main', 'user_id', 'group_id'
	];

	public function likes(){
		return $this->hasMany('App\Models\Wishlist\Like',['group_id','user'],['group_id','user_id']);
	}

	public function user(){
		return $this->hasOne('App\User', 'id', 'user_id');
	}

	public function price(){
		return $this->hasOne('App\Models\Company\CompanyPrice','id','price_id');
	}
}
