<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
	protected $table = 'wl_user_register';
	public $timestamps = false;

	protected $fillable = [
		'date', 'do', 'user', 'additionally'
	];

	public function action(){
		return $this->hasOne('App\Models\Log\LogAction','id','do');
	}
}
