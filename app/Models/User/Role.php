<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $table = 'wl_user_types';
	public $timestamps = false;
}
