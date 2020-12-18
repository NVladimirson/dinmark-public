<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	protected $dateFormat = 'U';
	protected $table = 'b2b_tickets';
	protected $fillable = [
		'subject', 'user_id', 'manager_id','new_for_user','new_for_manager','email_send'
	];

	public function messages(){
		return $this->hasMany('App\Models\Ticket\TicketMessage','ticket_id');
	}

	public function user(){
		return $this->hasOne('App\User','id', 'user_id');
	}

	public function manager(){
		return $this->hasOne('App\User','id', 'manager_id');
	}
}
