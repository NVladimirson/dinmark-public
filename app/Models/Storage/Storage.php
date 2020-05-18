<?php

namespace App\Models\Storage;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
	protected $table = 's_shopstorage';
	public $timestamps = false;

	public function storageProducts(){
		return $this->hasMany('App\Models\Storage\StorageProduct','storage_id');
	}
}
