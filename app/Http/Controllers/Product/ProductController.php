<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Currency;
use App\Models\Product\Product;
use App\Models\WlImage;
use Illuminate\Http\Request;
use LaravelLocalization;
use Artesaos\SEOTools\Facades\SEOTools;

class ProductController extends Controller
{
    public function index(){
		SEOTools::setTitle(trans('product.all_tab_name'));
    	return view('product.all');
	}

	public function allAjax(Request $request){
		$products = Product::select();
		$lang = LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale();
		$currencies = Currency::all();

		return datatables()
			->eloquent($products)
			->addColumn('check_html', function (Product $product) {
				return '<div class="checkbox checkbox-css">
						  <input type="checkbox" id="product-'.$product->id.'"  />
						  <label for="product-'.$product->id.'"> </label>
						</div>';
			})
			->addColumn('image_html', function (Product $product) {
				$ids[] = -$product->group;
				$photo = WlImage::where([
					['alias',$product->wl_alias],
					['content',$ids],
					['position',1],
				])->first();
				$src = env('DINMARK_URL').'images/dinmark_nophoto.jpg';
				if($photo){
					$src = 	env('DINMARK_URL').'images/shop/-'.$product->group.'/group_'.$photo->file_name;
				}

				return '<img src="'.$src.'" width="80">';
			})
			->addColumn('name', function (Product $product) use ($lang) {
				$content = $product->content->where('language',$lang)->where('alias',$product->wl_alias)->first();

				return $content?$content->name:'';
			})
			->addColumn('user_price', function (Product $product) use ($currencies) {
				$currency = $currencies->firstWhere('code',$product->currency);
				$price = $product->price;
				if($currency){
					$price *= $currency->currency;
				}
				$price *= auth()->user()->price->price;
				return number_format($price,2,'.',' ');
			})
			->addColumn('storage_html', function (Product $product) {
				return $product->storage_1.'/'.$product->termin;
			})

			->addColumn('actions', function (Product $product) {
				return '<button type="button" class="btn btn-sm btn-primary m-r-5"><i class="fas fa-eye"></i></button><button type="button" class="btn btn-sm btn-primary m-r-5"><i class="fas fa-star"></i></button><button type="button" class="btn btn-sm btn-primary m-r-5"><i class="fas fa-cart-plus"></i></button>';
			})
			->orderColumn('storage_html','storage_1 $1')
			->orderColumn('user_price', function ($product, $order){
				$product
					->leftJoin('s_currency', 's_shopshowcase_products.currency', '=', 's_currency.code')
					->select('s_shopshowcase_products.*', \DB::raw('s_shopshowcase_products.price * s_currency.currency AS price_with_currency'))
					->orderBy("price_with_currency", $order);
			})
			->filterColumn('storage_html', function($product, $keyword) {
				$product->where('storage_1', 'like',["%{$keyword}%"])->orWhere('termin', 'like',["%{$keyword}%"]);
			})
			->filter(function ($product) use ($request) {
				if (request()->has('storage_html')) {
					$product->whereHas('storage_1', 'like',"%" . request('storage_html') . "%")->orWhere()->whereHas('termin', 'like',"%" . request('storage_html') . "%");
				}
			}, true)
			->rawColumns(['image_html','check_html','actions'])
			->toJson();
	}
}
