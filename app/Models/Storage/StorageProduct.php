<?php

namespace App\Models\Storage;

use Illuminate\Database\Eloquent\Model;

class StorageProduct extends Model
{
	protected $table = 's_shopstorage_products';
	public $timestamps = false;

	public function storage(){
		return $this->hasOne('App\Models\Storage\Storage','id', 'storage_id');
	}
}
