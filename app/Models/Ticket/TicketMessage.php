<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
	protected $dateFormat = 'U';
	protected $table = 'b2b_ticket_messages';
	protected $fillable = [
		'text', 'is_new', 'ticket_id', 'user_id'
	];

	public function chat(){
		return $this->hasOne('App\Models\Ticket\Ticket','id', 'ticket_id');
	}

	public function user(){
		return $this->hasOne('App\User','id', 'user_id');
	}
}
