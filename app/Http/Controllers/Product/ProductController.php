<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Services\Product\CategoryServices;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use LaravelLocalization;
class ProductController extends Controller
{
    public function index(){
		SEOTools::setTitle(trans('product.all_tab_name'));

		$categories = CategoryServices::getNames(0);

    	return view('product.all',compact('categories'));
	}

	public function category($id){
    	$page_name = CategoryServices::getName($id);
		SEOTools::setTitle($page_name);

		$categories = CategoryServices::getNames($id);
		$breadcrumbs = CategoryServices::getBreadcrumbs($id);

		return view('product.all',compact('categories','id', 'page_name', 'breadcrumbs'));
	}

	public function allAjax(Request $request){
		$products = Product::select();

		$ids = null;
		if($request->has('category_id')){
			$products->whereIn('group',CategoryServices::getAllChildrenCategoriesID($request->category_id));
		}
		if($request->has('search')){
			$ids = \App\Services\Product\Product::getIdsSearch(request('search')['value']);
		}

		return datatables()
			->eloquent($products)
			->addColumn('check_html', function (Product $product) {
				return '<div class="checkbox checkbox-css">
						  <input type="checkbox" id="product-'.$product->id.'"  />
						  <label for="product-'.$product->id.'"> </label>
						</div>';
			})
			->addColumn('image_html', function (Product $product) {
				$src = \App\Services\Product\Product::getImagePath($product);

				return '<img src="'.$src.'" width="80">';
			})
			->addColumn('name_html', function (Product $product){
				$name = \App\Services\Product\Product::getName($product);
				return '<a href="'.route('products.show',[$product->id]).'">'.$name.'</a>';
			})
			->addColumn('article_show_html', function (Product $product) {
				return '<a href="'.route('products.show',[$product->id]).'">'.$product->article_show.'</a>';
			})
			->addColumn('user_price', function (Product $product) {
				return \App\Services\Product\Product::getPrice($product);
			})
			->addColumn('storage_html', function (Product $product) {
				$value = trans('product.storage_empty');
				if($product->storages){
					$storage = $product->storages->firstWhere('is_main',1);
					if($storage){
						$value = $storage->amount.' / '.$storage->storage->term;
					}
				}
				return $value;
			})

			->addColumn('actions', function (Product $product) {
				return '<a href="'.route('products.show',[$product->id]).'" class="btn btn-sm btn-primary m-r-5"><i class="fas fa-eye"></i></a><button type="button" class="btn btn-sm btn-primary m-r-5"><i class="fas fa-star"></i></button><button type="button" class="btn btn-sm btn-primary m-r-5"><i class="fas fa-cart-plus"></i></button>';
			})
			->orderColumn('storage_html','storage_1 $1')
			->orderColumn('article_show_html','article_show $1')
			->orderColumn('user_price', function ($product, $order){
				$product
					->leftJoin('s_currency', 's_shopshowcase_products.currency', '=', 's_currency.code')
					->select('s_shopshowcase_products.*', \DB::raw('s_shopshowcase_products.price * s_currency.currency AS price_with_currency'))
					->orderBy("price_with_currency", $order);
			})
			->filterColumn('storage_html', function($product, $keyword) {
				$product->where('storage_1', 'like',["%{$keyword}%"])->orWhere('termin', 'like',["%{$keyword}%"]);
			})
			->filterColumn('article_show_html', function($product, $keyword) {
				$product->where('article_show', 'like',["%{$keyword}%"]);
			})
			->filterColumn('name_html', function($product, $keyword) use($ids) {
				if($ids){
					$product->whereIn('id',$ids);
				}else{
					$product->select();
				}
			})
			->filter(function ($product) use ($request,$ids) {
				if (request()->has('storage_html')) {
					$product->whereHas('storage_1', 'like',"%" . request('storage_html') . "%")->orWhere()->whereHas('termin', 'like',"%" . request('storage_html') . "%");
				}
				if (request()->has('article_show_html')) {
					$product->whereHas('article_show', 'like',"%" . request('article_show_html') . "%");
				}
				if(request()->has('name_html')){
					$product->whereIn('id',$ids);
				}
			}, true)
			->rawColumns(['name_html','article_show_html','image_html','check_html','actions'])
			->toJson();
	}

	public function show($id){
		$product = Product::find($id);

		$productName = \App\Services\Product\Product::getName($product);
		$imagePath = \App\Services\Product\Product::getImagePath($product);
		$price = \App\Services\Product\Product::getPrice($product);
		$userPrice = \App\Services\Product\Product::getUserPrice($product);

		SEOTools::setTitle($productName);

		return view('product.index', compact('product','productName','imagePath', 'price', 'userPrice'));
	}
}
