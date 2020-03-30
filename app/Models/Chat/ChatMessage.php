<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
	protected $table = 'b2b_chat_messages';
	protected $fillable = [
		'text', 'is_new', 'chat_id', 'user_id'
	];

	public function chat(){
		return $this->hasOne('App\Models\Chat\Chat','id', 'chat_id');
	}

	public function user(){
		return $this->hasOne('App\User','id', 'user_id');
	}
}
