<?php

namespace App\Models\Reclamation;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
	protected $table = 'b2b_reclamations';
	const CREATED_AT = 'date_add';
	const UPDATED_AT = 'date_update';
	protected $dateFormat = 'U';

	protected $fillable = [
		'ttn',
		'author',
		'file',
	];

	public function user(){
		return $this->hasOne('App\User','id','author');
	}

	public function products(){
	    return $this->hasMany('App\Models\Reclamation\ReclamationProduct','reclamation_id');
    }
}
