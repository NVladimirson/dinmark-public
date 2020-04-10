<?php

namespace App\Models\Wishlist;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	protected $table = 's_likes';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_update';
	protected $dateFormat = 'U';

	protected $fillable = [
		'user', 'alias', 'content', 'group_id', 'status'
	];
}
