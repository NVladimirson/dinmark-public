<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;

use App\Jobs\ProductOptions;
use App\Models\Content;
use App\Models\Order\OrderProduct;
use App\Models\Product\GetPrice;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductOption;
use App\Models\Product\ProductOptionName;
use App\Services\Product\CategoryServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Order\OrderServices;
use App\Services\Product\CatalogServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use LaravelLocalization;

class ProductController extends Controller
{


    public function index(){
        SEOTools::setTitle(trans('product.all_tab_name'));
        $categories = CategoryServices::getNames(0);
        $wishlists = CatalogServices::getByCompany();
        $orders = OrderServices::getByCompany();
        $terms = CategoryServices::getTermsForSelect();
        $filters = CategoryServices::getFilters();
        //dd($filters);
        return view('product.all',compact('categories','wishlists', 'orders', 'terms','filters'));
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

    public function optionFilters(Request $request){
        $request_options = $request->filter_with_options;

        foreach($request_options as $option){
            $key = explode(';',$option)[0];
            $value = explode(';',$option)[1];
            $option_map[$key] = $value;
        }

        $language = ProductOptionName::find(array_key_first($option_map))->language;
        $object = \Cache::get('filters_'.$language);

        $filter_map = array();
        foreach ($object as $filtername => $filtervalues){
            foreach ($filtervalues as $valuename => $products){
                foreach ($products as $product_id => $product_data){
                    $filter_map[$product_id][$product_data['value_id']] = $valuename;
                }
            }
        }
        //dd($filter_map);
        $filter_map = Arr::where($filter_map, function ($value) use ($option_map) {
            if(count($value)>=count($option_map)){
                return $value;
            }
        });//filtering

        if(isset($filter_map)){
            foreach ($filter_map as $product_id => $valuedata){
                //dd($option_map);
                foreach ($option_map as $value_id => $valuename){
                    if(!in_array($value_id,array_keys($valuedata))){
                        $contains = false;
                        break;
                    }
                    $contains = true;
                }
                if(!$contains){
                    unset($filter_map[$product_id]);
                }
            }
        }

        $valid_options = ['checked' => [],'availiable' => []];
        if(isset($filter_map)){
            foreach ($filter_map as $product_id => $valuedata) {
                foreach ($valuedata as $value_id => $valuename){
                    if(in_array($value_id,array_keys($option_map))){
                        $valid_options['checked'][$value_id] = $valuename;
                    }
                    $valid_options['availiable'][$value_id] = $valuename;
                }
            }

        }
        return $valid_options;

    }


    public function allAjax(Request $request){
        $products = Product::with(['storages','content','options']);

        if(!empty($request->categories)){
            $selected_items = array_values(explode(",",$request->categories));
            $res = $selected_items;
            foreach ($selected_items as $key => $parent) {
                $childs = CategoryServices::getAllChildrenCategoriesID($parent);
                // $res = Arr::crossJoin($res,$childs);
                foreach ($childs as $key => $child) {
                    $res[] = $child;
                }
            }
            $products = $products->whereIn('group', $res);
        }

        if($request->term){
            $term = $request->term;
            $products = $products->whereHas('storages', function($storages) use($term){
                $storages->whereHas('storage',function($storage) use($term){
                    $storage->where('term',$term);
                });
            });
        }

        if($request->instock == 'true')
        {
            $products = $products->whereHas('storages', function($q){
                $q->where('amount','>',0);
            });
        }

        if($request->new){
            $order_products = \DB::select('SELECT product_id, COUNT(*)
              FROM s_cart_products
              GROUP BY product_id
              HAVING COUNT(*) >= 5
              ');

            $filtered = array();
            foreach ($order_products as $no => $order_product){
                $filtered[] = $order_product->product_id;
            }

            $products = $products->whereIn('id', $filtered);


        }

        if($request->hits){
            $products = $products->where('date_add','>',Carbon::now()->subDays(7)->timestamp);
        }

        if($request->discount){
            $products = $products->where('old_price','!=',0);
        }


        if($request->filter_with_options){
            $request_options = explode(',',$request->filter_with_options);

            foreach($request_options as $option){
                $key = explode(';',$option)[0];
                $value = explode(';',$option)[1];
                $option_map[$key] = $value;
            }


            $language = ProductOptionName::find(array_key_first($option_map))->language;
            $object = \Cache::get('filters_'.$language);
            if(!$object){
                dispatch(new ProductOption($language));
            }

            $filter_map = array();
            foreach ($object as $filtername => $filtervalues){
                foreach ($filtervalues as $valuename => $product_ids){
                    foreach ($product_ids as $product_id => $product_data){
                        $filter_map[$product_id][$product_data['value_id']] = $valuename;
                    }
                }
            }
            //dd($filter_map);
            $filter_map = Arr::where($filter_map, function ($value) use ($option_map) {
                if(count($value)>=count($option_map)){
                    return $value;
                }
            });//filtering

            if(isset($filter_map)){
                foreach ($filter_map as $product_id => $valuedata){
                    //dd($option_map);
                    foreach ($option_map as $value_id => $valuename){
                        if(!in_array($value_id,array_keys($valuedata))){
                            $contains = false;
                            break;
                        }
                        $contains = true;
                    }
                    if(!$contains){
                        unset($filter_map[$product_id]);
                    }
                }
            }

            if (isset($filter_map)){
                $valid_ids = array_keys($filter_map);
            }
            else{
                $valid_ids = [];
            }
            info($valid_ids);
            $products = $products->whereIn('id',array_values($valid_ids));
        }

        $ids = null;

        if($request->has('search')){
            $ids = \App\Services\Product\Product::getIdsSearch(request('search')['value']);
        }

        return datatables()
            ->eloquent($products)
            ->addColumn('check_html', function (Product $product) {
                return '<div class="checkbox checkbox-css">
						  <input type="checkbox" id="product-'.$product->id.'" class="intable" onclick="(function(){
								var pid = '."$product->id".';
								var selected_products = document.getElementById('."'selected_products'".');
								textContent = selected_products.textContent;
								selected_products_arr = textContent.split('."','".');
								const index = selected_products_arr.indexOf(String(pid));
//								console.log(index);
										if (index > -1) {
  										selected_products_arr.splice(index, 1);
										}
										else{
											if(selected_products_arr.length === 1 && selected_products_arr[0] === '."''".'){
												selected_products_arr = [String(pid)];
											}
											else{
												selected_products_arr.push(String(pid));
											}
										}
										selected_products.textContent = selected_products_arr.toString();

//										 console.log(selected_products_arr);
                                        
							})();"/>
						  <label for="product-'.$product->id.'"> </label>
						</div>';
            })
            ->addColumn('image_html', function (Product $product) {
                $src = \App\Services\Product\Product::getImagePath($product);

                return '<img src="'.$src.'" width="80">';
            })
            ->addColumn('name_html', function (Product $product){
                $name = \App\Services\Product\Product::getName($product);
                return '<a class="data-product_name" href="'.route('products.show',[$product->id]).'">'.$name.'</a>';
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
                    $storages = $product->storages;
                    if($storages){
                        $value = '';
                        //dd($storages);
                        foreach ($storages as $key => $storage) {
                            $term = $storage->storage->term;
                            if(Str::length($term) == 1){
                                if(intval($term) == 1){
                                    $days =  'роб. доба';
                                }
                                else if((intval($term) <= 4) && intval($term) >= 2){
                                    $days =  'роб. доби';
                                }
                                else{
                                    $days =  'роб. діб';
                                }
                            }
                            else{
                                $tens = substr($term,-2);
                                $ones = substr($term,-1);
                                if($tens == 1){
                                    $days =  'роб. діб';
                                }
                                else{
                                    if(intval($ones) == 1){
                                        $days =  'роб. доба';
                                    }
                                    else if((intval($term) <= 4) && intval($term) >= 2){
                                        $days =  'роб. доби';
                                    }
                                    else{
                                        $days =  'роб. діб';
                                    }
                                }
                            }

                            $value .= $storage->storage->name.': '.CatalogServices::dayrounder($storage->amount).' / '.$term.' '.$days."<br>";
                        }
                        //$value = substr($value,0,-2);
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
            ->rawColumns(['name_html','article_show_html','image_html','check_html','actions','switch','storage_html'])
            ->toJson();
    }

    public function getNode(Request $request){
        $tree = CategoryServices::getNodeAjax($request->id);
        return array_values($tree);
    }

    public function show($id){
        $product = Product::find($id);

        $productName = \App\Services\Product\Product::getName($product);
        $productText = \App\Services\Product\Product::getText($product);
        $imagePath = \App\Services\Product\Product::getImagePathThumb($product);
        $productPhotos = \App\Services\Product\Product::getImagePathThumbs($product);
        $productVideo = \App\Services\Product\Product::getVideo($product);
        $productPDF = \App\Services\Product\Product::getPDF($product);
        $price = \App\Services\Product\Product::getPrice($product);
        $limit1 = ($product->limit_1 > 0)? (\App\Services\Product\Product::getPriceWithCoef($product,0.97).' '.trans('product.table_header_price_from',['quantity' => $product->limit_1])) : '-';
        $limit2 = ($product->limit_2 > 0)? (\App\Services\Product\Product::getPriceWithCoef($product,0.93).' '.trans('product.table_header_price_from',['quantity' => $product->limit_2])) : '-';

        $orders = OrderServices::getByCompany();
        $basePrice = \App\Services\Product\Product::getBasePrice($product);
        $wishlists = CatalogServices::getByCompany();

        $storage_prices = [];
        $storage_raw_prices = [];

        foreach ($product->storages as $storage){
            $storage_prices[$storage->id] = \App\Services\Product\Product::getPrice($product,$storage->id);
            $storage_raw_prices[$storage->id] = \App\Services\Product\Product::calcPrice($product,$storage->id);
        }
        SEOTools::setTitle($productName);

        return view('product.index', compact('product','productName', 'productText', 'imagePath', 'productPhotos', 'productVideo', 'productPDF', 'price', 'basePrice',
            'wishlists', 'orders', 'limit1', 'limit2', 'storage_prices','storage_raw_prices'));
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
