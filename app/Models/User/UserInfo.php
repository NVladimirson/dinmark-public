<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
	protected $table = 'wl_user_info';
	public $timestamps = false;

	protected $fillable = [
		'user', 'field', 'value', 'date'
	];
}
