<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
	protected $table = 'b2b_chats';
	protected $fillable = [
		'subject', 'user_id', 'manager_id'
	];

	public function messages(){
		return $this->hasMany('App\Models\Chat\ChatMessage','chat_id');
	}

	public function user(){
		return $this->hasOne('App\User','id', 'user_id');
	}

	public function manager(){
		return $this->hasOne('App\User','id', 'manager_id');
	}
}
