<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\WlImage;
use Illuminate\Http\Request;

use Artesaos\SEOTools\Facades\SEOTools;

class ProductController extends Controller
{
    public function index(){
		SEOTools::setTitle(trans('product.all_tab_name'));
    	return view('product.all');
	}

	public function allAjax(){
		$products = Product::select();

		return datatables()
			->eloquent($products)
			->addColumn('image_html', function (Product $product) {
				$ids[] = -$product->group;
				$photo = WlImage::where([
					['alias',8],
					['content',$ids],
					['position',1],
				])->first();
				$src = env('DINMARK_URL').'images/dinmark_nophoto.jpg';
				if($photo){
					$src = 	env('DINMARK_URL').'images/shop/-'.$product->group.'/group_'.$photo->file_name;
				}

				return '<img src="'.$src.'" width="80">';
			})
			->rawColumns(['image_html'])
			->toJson();
	}
}
