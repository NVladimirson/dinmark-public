<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class ProductShopStorage extends Model
{
	protected $table = 's_shopstorage_products';
	public $timestamps = false;

	public function content(){
		return $this->hasMany('App\Models\Content','content');
	}

	public function storages(){
		return $this->hasMany('App\Models\Storage\StorageProduct','product_id');
	}

	public function options(){
		return $this->hasMany('App\Models\Product\ProductOption','product');
	}

	public function likes(){
		return $this->hasMany('App\Models\Wishlist\Like','content');
	}

	public function holdingArticles(){
		return $this->hasMany('App\Models\Product\CompanyProductArticle','product_id');
	}
}
