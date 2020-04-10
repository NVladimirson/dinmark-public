<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Wishlist\Like;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function addToCatalog($id, Request $request){
    	Like::updateOrCreate([
			'user' => auth()->user()->id,
			'alias' => 8,
			'content' =>$request->product_id,
			'group_id' => $id,
		],[
			'status' => 1
		]);

    	return 'ok';
	}
}
