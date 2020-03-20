<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserDataChangeRequest extends Model
{
	protected $fillable = [
		'type', 'value', 'user_id'
	];
}
