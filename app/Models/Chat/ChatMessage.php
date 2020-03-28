<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
	protected $fillable = [
		'text', 'is_new', 'chat_id', 'user_id'
	];

	public function user(){
		return $this->hasOne('App\User','id', 'user_id');
	}
}
