<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\GetPrice;
use App\Models\Product\Product;
use App\Services\Order\OrderServices;
use App\Services\Product\CategoryServices;
use App\Services\Product\CatalogServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use LaravelLocalization;
use Illuminate\Support\Str;

class ProductController extends Controller
{
	public function index(){
		SEOTools::setTitle(trans('product.all_tab_name'));

		$categories = CategoryServices::getNames(0);
		$wishlists = CatalogServices::getByCompany();
		$orders = OrderServices::getByCompany();


    	return view('product.all',compact('categories','wishlists', 'orders'));
	}

	public function category($id){
    	$page_name = CategoryServices::getName($id);
		SEOTools::setTitle($page_name);

		$categories = CategoryServices::getNames($id);
		$breadcrumbs = CategoryServices::getBreadcrumbs($id);
		$wishlists = CatalogServices::getByCompany();
		$orders = OrderServices::getByCompany();

		return view('product.all',compact('categories','id', 'page_name', 'breadcrumbs','wishlists', 'orders'));
	}

	public function allAjax(Request $request){

		if(Str::contains(url()->previous(), 'instock'))
		{
			$products = Product::whereHas('storages', function($q){
          $q->where('amount', '>', '0');
          $q->where('is_main', '!=', '0');
      });
		}
		else{
			$products = Product::with(['storages','content']);
		}
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
                if(\App\Services\Product\Product::hasAmount($product->storages)){
                    return \App\Services\Product\Product::getPrice($product);
                }
                return number_format(0,2,'.',' ');
			})
			->addColumn('html_limit_1', function (Product $product) {
				if($product->limit_1 > 0){
                    if(\App\Services\Product\Product::hasAmount($product->storages)){
                        return \App\Services\Product\Product::getPriceWithCoef($product,0.97).' '.trans('product.table_header_price_from',['quantity' => $product->limit_1]);
                    }
				}

                return '-';
			})
			->addColumn('html_limit_2', function (Product $product) {
				if($product->limit_2 > 0){
                    if(\App\Services\Product\Product::hasAmount($product->storages)){
                        return \App\Services\Product\Product::getPriceWithCoef($product,0.93).' '.trans('product.table_header_price_from',['quantity' => $product->limit_2]);
                    }
				}

                return '-';
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
				$storage = $product->storages->firstWhere('is_main',1);
				$hasStorage = \App\Services\Product\Product::hasAmount($product->storages);
                $name = \App\Services\Product\Product::getName($product);
				return view('product.include.action_buttons',compact('product', 'name', 'storage', 'hasStorage'));
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
			->rawColumns(['name_html','article_show_html','image_html','check_html','actions','switch'])
			->toJson();
	}

	public function show($id){
		$product = Product::find($id);

		$productName = \App\Services\Product\Product::getName($product);
		$imagePath = \App\Services\Product\Product::getImagePathThumb($product);
		$price = \App\Services\Product\Product::getPrice($product);
		$limit1 = ($product->limit_1 > 0)? (\App\Services\Product\Product::getPriceWithCoef($product,0.97).' '.trans('product.table_header_price_from',['quantity' => $product->limit_1])) : '-';
		$limit2 = ($product->limit_2 > 0)? (\App\Services\Product\Product::getPriceWithCoef($product,0.93).' '.trans('product.table_header_price_from',['quantity' => $product->limit_2])) : '-';

		$basePrice = \App\Services\Product\Product::getBasePrice($product);
		$wishlists = CatalogServices::getByCompany();
		$orders = OrderServices::getByCompany();

		$storage_prices = [];
		$storage_raw_prices = [];

		foreach ($product->storages as $storage){
            $storage_prices[$storage->id] = \App\Services\Product\Product::getPrice($product,$storage->id);
            $storage_raw_prices[$storage->id] = \App\Services\Product\Product::calcPrice($product,$storage->id);
        }
		SEOTools::setTitle($productName);

		return view('product.index', compact('product','productName','imagePath', 'price', 'basePrice', 'wishlists', 'orders', 'limit1', 'limit2', 'storage_prices','storage_raw_prices'));
	}

	public function search(Request $request){
		$search = $request->name;
		$formatted_data = [];

		$ids = \App\Services\Product\Product::getIdsSearch($search);

		$products = Product::whereIn('id',$ids)
			->orWhere([
				['article','like',"%".$search."%"],
			])->orWhere([
				['article_show','like',"%".$search."%"],
			])
			->orderBy('article')
			->limit(10)
			->get();

		foreach ($products as $product) {
			$name = \App\Services\Product\Product::getName($product);
			$storage = $product->storages->firstWhere('is_main',1);
			$min = 0;
			$max = 0;
			$storage_id = 1;
			if($storage){
				$min = $storage->package;
				$max = $storage->amount;
				$storage_id = $storage->storage_id;
			}

			$formatted_data[] = [
				'id' => $product->id,
				'text' => $name.' ('.$product->article_show.')',
				'min' => $min,
				'max' => $max,
				'storage_id' => $storage_id,
			];
		}


		return \Response::json($formatted_data);
	}

	public function find(Request $request)
	{
		$search = $request->search;
		$formatted_data = [];

		$ids = \App\Services\Product\Product::getIdsSearch($search);

		$products = Product::with(['content'])->whereIn('id',$ids)
			->orWhere([
				['article','like',"%".$search."%"],
			])->orWhere([
				['article_show','like',"%".$search."%"],
			])
			->orderBy('article')
			->limit(20)
			->get();

		foreach ($products as $product) {
			$name = \App\Services\Product\Product::getName($product);

			$formatted_data[] = [
				'id' => $product->id,
				'text' => $name.' ('.$product->article_show.')',
			];
		}

		return view('product.search',compact('formatted_data'));
	}

	public function getPrice($id, Request $request)
    {
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'comment' => $request->comment,
        ];
        \App\Services\Product\Product::getPriceRequest($id, $request->quantity, $data);

        return 'ok';
    }
}
