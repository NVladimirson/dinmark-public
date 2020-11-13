<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;


use App\Jobs\ProductOptionFiltersJob;
use App\Models\Content;
use App\Models\Order\Implementation;
use App\Models\Order\ImplementationProduct;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use App\Models\Reclamation\ReclamationProduct;
use App\Services\Miscellenous\GlobalSearchService;
use App\Services\Product\CategoryServices;
use App\Services\Product\Product as ProductServices;
use Illuminate\Support\Facades\Cache;
use App\Services\Order\OrderServices;
use App\Services\Product\CatalogServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use LaravelLocalization;
use PhpParser\Node\Expr\Array_;
use App\Models\Product\ProductOption;

class ProductController extends Controller
{


    public function index(){
        SEOTools::setTitle(trans('product.all_tab_name'));
        $categories = CategoryServices::getNames(0);
        $wishlists = CatalogServices::getByCompany();
        $orders = OrderServices::getByCompany();
        $terms = CategoryServices::getTermsForSelect();
        $filters = CategoryServices::getOptionFilters();
        $dinmark_url = \Config::get('values.dinmarkurl');
        //dd($filters);
        return view('product.all',compact('categories','wishlists', 'orders', 'terms','filters','dinmark_url'));
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
        info($request_options);
        if(!$request_options){
            return $valid_options = ['checked' => [],'available' => []];
        }

        foreach($request_options as $option){
            $key = explode(';',$option)[0];
            $value = explode(';',$option)[1];
            $option_map[$key] = $value;
        }
        $language = CategoryServices::getLang();

        $products = ProductOption::whereIn('value',array_keys($option_map))->pluck('product')->toArray();

        $query = 'SELECT DISTINCT s_shopshowcase_product_options.value FROM s_shopshowcase_product_options WHERE product in (';

        foreach ($products as $key => $value) {
          if($key != array_key_last($products)){
          $query .= $value.',';
          }
          else{
          $query .= $value.')';
          }
        }

        $options = \DB::select($query);
        foreach ($options as $key => $value) {
          if(in_array($value->value,$option_map)){
            continue;
          }
          $available[$value->value] = 'opt';
        }

        $valid_options = ['checked' => $option_map,'available' => $available];
        return $valid_options;

    }

    public function test(Request $request){


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
            $products = $products->where('date_add','>',Carbon::now()->subDays(7)->timestamp);
        }

        if($request->hits){
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

        if($request->discount){
            $products = $products->where('old_price','!=',0);
        }


        if($request->filter_with_options){
            $request_options = explode(',',$request->filter_with_options);
            $valid_ids = Array();
            $filters = CategoryServices::getOptionFilters();
            foreach ($filters as $option_id=>$filterdata){
                foreach($filterdata['options'] as $branch_id => $data){
                    if(in_array($branch_id,$request_options)){
                        if(empty($valid_ids)){
                            $valid_ids = $data['products'];
                        }else{
                            $valid_ids = array_intersect($valid_ids,$data['products']);
                        }

                    }
                }
            }
            if(!empty($valid_ids)){
                $products = $products->whereIn('id',array_values($valid_ids));
            }
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

            $spans = '';

            if($product->old_price != 0){
                $spans .= '<span class="bage-default bage-sale">SALE</span>';
            }

            if($product->date_add >= Carbon::now()->subDays(7)->timestamp){
                $spans .= '<span class="bage-default bage-new">NEW</span>';
            }

            if(\DB::select('SELECT COUNT(*) as count FROM s_cart_products WHERE product_id = '.$product->id.'')[0]->count >= 5){
                $spans .= '<span class="bage-default bage-hits">HITS</span>';
            }


            return '<div class="product-image"><img src="'.$src.'" alt="'.env('DINMARK_URL').'images/dinmark_nophoto.jpg" width="60">
                        <div class="wrap-label">
                        '.$spans.'
                        </div>
                    </div>';
        })
        ->addColumn('name_article_html', function (Product $product){
            $name = \App\Services\Product\Product::getName($product);
            return '<a class="data-product_name" href="'
            .route('products.show',[$product->id]).'">'.$name.'</a><br>'.
            '<a href="'.route('products.show',[$product->id]).'">'.$product->article_show.'</a>';
        })
//            ->addColumn('name_html', function (Product $product){
//                $name = \App\Services\Product\Product::getName($product);
//                return '<a class="data-product_name" href="'.route('products.show',[$product->id]).'">'.$name.'</a>';
//            })
//            ->addColumn('article_show_html', function (Product $product) {
//                return '<a href="'.route('products.show',[$product->id]).'">'.$product->article_show.'</a>';
//            })

            ->addColumn('retail_price', function (Product $product) {
                if(\App\Services\Product\Product::hasAmount($product->storages)){
                    return '<p id="retail_price_'.$product->id.'"><span>'.ProductServices::getBasePrice($product).'</span></p>';
                }
                return number_format(0,2,'.',' ');
            })
            ->addColumn('user_price', function (Product $product) {
                if(\App\Services\Product\Product::hasAmount($product->storages)){
                    return '<p id="user_price_'.$product->id.'"><span>'.ProductServices::getPrice($product).'</span></p>';
                }
                return number_format(0,2,'.',' ');
                //return '<p id="user_price_'.$product->id.'"><span></span></p>';
            })
            ->addColumn('html_limit_1', function (Product $product) {
                $storage = $product->storages->firstWhere('is_main',1);
                if(isset($storage->limit_1) && $storage->limit_1!=0){
                    $price_limit = ProductServices::getPriceWithCoef($product,0.97);
                    $limit = $storage->limit_1;
                    return '<p id="limit_1_'.$product->id.'" style="color: #96ca0a" ><span class="limit_amount_price_1">'.$price_limit.
                        '</span><br><span class="limit_amount_quantity_1">'.'>'.$limit.'</span></p>';
                }
                else{
                    return '<p id="limit_1_'.$product->id.'" style="color: #f0c674" ><span class="limit_amount_price_1"> -
                        </span><br><span class="limit_amount_quantity_1"></span></p>';
                }
            })
            ->addColumn('html_limit_2', function (Product $product) {
                $storage = $product->storages->firstWhere('is_main',1);
                if(isset($storage->limit_2) && $storage->limit_2!=0){
                    $price_limit = ProductServices::getPriceWithCoef($product,0.93);
                    $limit = $storage->limit_2;
                    return '<p id="limit_2_'.$product->id.'" style="color: #f0c674" ><span class="limit_amount_price_2">'.$price_limit.
                        '</span><br><span class="limit_amount_quantity_2">'.'>'.$limit.'</span></p>';
                }
                else{
                    return '<p id="limit_2_'.$product->id.'" style="color: #f0c674" ><span class="limit_amount_price_2"> -
                        </span><br><span class="limit_amount_quantity_2"></span></p>';
                }

            })
            ->addColumn('storage_html', function (Product $product) {
                $value = trans('product.storage_empty');
                $emptyvalue = trans('product.storage_choose');
                if($product->storages){
                    $storages = $product->storages;
                    if(count($storages)){
                        $value = '<select onchange="initCalc(this)" class="custom-select" product_id="'.$product->id.'" id="storage_product_'.$product->id.'">';
                        //$value .= "<option value='0'>$emptyvalue</option>";
                        if(isset($product->storages->firstWhere('is_main',1)->storage_id)){
                            $main_storage = $product->storages->firstWhere('is_main',1)->storage_id;
                        }
                        else{
                            $main_storage = 0;
                        }
                        foreach ($storages as $key => $storage) {
                            $term = $storage->storage->term;
                            $days = ProductServices::getStingDays($term);
                            $name = CatalogServices::dayrounder($storage->amount).
                            ' / '.$term.' '.$days.' ('.$storage->storage->name.')';
                            $value .= '<option value="'.$storage->storage->id.'" package_min="'.$storage->package.'"
                            package_max="'.$storage->amount.'"';
                            if($storage->storage->id == $main_storage){
                                $value .= 'selected>'.$name.'</option>';
                            }
                            else{
                                $value .= '>'.$name.'</option>';
                            }
                        }
                        $value .= '</select>';
                    }
                    $value .= '</select>';
                }
                return $value;
            })
            ->addColumn('calc_quantity', function (Product $product) {
                $storage = $product->storages->firstWhere('is_main',1);

                if(\App\Services\Product\Product::hasAmount($product->storages)){
                    $package = $storage->package;
                    return '
                    <input id="calc_quantity_'.$product->id.'" onchange="changeamount(this)" type="number"
                    name="quantity" class="form-control m-b-15" style="max-width: 80px;margin-bottom: 0px!important;"
                    value="'.$storage->package.'" min="'.$storage->package.'" step="'.$storage->package.'" max="'.$storage->amount.'"/>';
                }
                else{
                    return '
                    <input id="calc_quantity_'.$product->id.'" onchange="changeamount(this)" type="number"
                    name="quantity" class="form-control m-b-15" style="max-width: 80px;margin-bottom: 0px!important; display:none"
                    value="0" min="0" step="10" data-max="1000"/>';
                }
            })
            ->addColumn('package_weight', function (Product $product) {
                $storage = $product->storages->firstWhere('is_main',1);
                if(isset($storage->package)){
                    $package = $storage->package;
                    $unit = $product->unit;

                    preg_match_all('!\d+!', $unit, $isnumber);
                    if(!empty($isnumber[0])){
                        $unitnumber = $isnumber[0][0];
                    }else{
                        $unitnumber = 1;
                    }//сори

                    $weight = $product->weight * ($storage->package/$unitnumber);
                    return '
                <p id="package_weight_'.$product->id.'">
                <span class="multiplier">1</span> x <span class="package">'.$package.'</span><br>
                <span class="weight">'.number_format($weight,3,'.',',').'</span>
                </p>';
                }else{
                    return '
                <p id="package_weight_'.$product->id.'">
                <span class="multiplier"></span><span class="package"></span><br>
                <span class="weight"></span>
                </p>';
                }
            })
            ->addColumn('sum_w_taxes', function (Product $product) {
                $storage = $product->storages->firstWhere('is_main',1);
                if(isset($storage)){
                    $price = ProductServices::getPriceUnformatted($product);
                    // 'discount' => $discount,
                    //'discountamount' => number_format($multiplier*ProductServices::getPriceUnformatted($productinfo,$storage_id) - $multiplier*$price,2,'.',' '),
                    return '<p id="sum_w_taxes_'.$product->id.'"><span class="price">'.number_format($price,2,'.',' ').'</span> <br>
                <span class="discount">-0%</span> <span class="discountamount">'.number_format(0,2,'.',' ').'</span> </p>';
                }else{
                    return '<p id="sum_w_taxes_'.$product->id.'"><span class="price"></span> <br>
                <span class="discount"></span> <span class="discountamount"></span> </p>';
                }

            })
            ->addColumn('actions', function (Product $product) {
                $storage = $product->storages->firstWhere('is_main',1);
                $hasStorage = \App\Services\Product\Product::hasAmount($product->storages);
                $src = \App\Services\Product\Product::getImagePath($product);
                $name = \App\Services\Product\Product::getName($product);
                return view('product.include.action_buttons',compact('product','hasStorage','name','src','storage'));
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
            ->rawColumns(['name_article_html','html_limit_1','html_limit_2','image_html','check_html','actions','switch','storage_html','calc_quantity','sum_w_taxes','package_weight','retail_price','user_price'])
            ->toJson();
    }

    public function getNode(Request $request){
        $tree = CategoryServices::getNodeAjax($request->id);
        return array_values($tree);
    }

    public function priceCalc(Request $request){
        $product_id = $request->product_id;
        $storage_id = $request->storage_id;
        $amount = $request->amount;

        $productinfo = Product::find($product_id);
        $name = ProductServices::getName($productinfo);
        $storageinfo = $productinfo->storages->where('storage_id',$storage_id)->first();
        $package = $storageinfo->package;
        $package ? ($storageamount = $storageinfo->amount-($storageinfo->amount%$storageinfo->package)) :
            ($storageamount = $storageinfo->amount-($storageinfo->amount%100));
        $three_percent_discount_limit = $storageinfo->limit_1;
        $seven_percent_discount_limit = $storageinfo->limit_2;
        if (($amount >= $seven_percent_discount_limit) && $seven_percent_discount_limit){
            $price = ProductServices::getPriceWithCoefUnformatted($productinfo,0.93);
            $discount = '7%';
        }
        else if(($amount >= $three_percent_discount_limit) && $three_percent_discount_limit){
            $price = ProductServices::getPriceWithCoefUnformatted($productinfo,0.97);
            $discount = '3%';
        }
        else{
            $price = ProductServices::getPriceUnformatted($productinfo,$storage_id);
            $discount = '0%';
        }


        $multiplier = $amount/$package - $amount%$package;
        $unit = $productinfo->unit;
        preg_match_all('!\d+!', $unit, $isnumber);
        if(!empty($isnumber[0])){
            $unitnumber = $isnumber[0][0];
        }else{
            $unitnumber = 1;
        }//сори

        $weight = $productinfo->weight * ($amount/$unitnumber);
        //number_format($weight,3,'.',' ')

        $retail_price = number_format(ProductServices::getBasePriceUnformatted($productinfo,$storage_id)*$multiplier,2,'.',' ');

        $user_price = number_format(ProductServices::getPriceUnformatted($productinfo,$storage_id)*$multiplier,2,'.',' ');

        $response = [
            'name' => $name,
            'retail_price' => $retail_price,
            'user_price' => $user_price,
            'multiplier' => $multiplier,
            'storageamount' => $storageamount,
            'package' => $package,
            'weight' => number_format($weight,3,'.',' '),
            'price' => number_format($multiplier*$price,2,'.',' '),
            'discount' => $discount,
            'discountamount' => number_format($multiplier*ProductServices::getPriceUnformatted($productinfo,$storage_id) - $multiplier*$price,2,'.',' '),
            'limit_amount_price_1' => ProductServices::getPriceWithCoef($productinfo,0.97),
            'limit_amount_price_2' => ProductServices::getPriceWithCoef($productinfo,0.93),
            'limit_amount_quantity_1' => $three_percent_discount_limit,
            'limit_amount_quantity_2' => $seven_percent_discount_limit,
        ];

        return $response;
    }

    public function show($id){
        $product = Product::find($id);

        $productName = \App\Services\Product\Product::getName($product);
        $productText = \App\Services\Product\Product::getText($product);
        $imagePath = \App\Services\Product\Product::getImagePathThumb($product);
        $imagePathFull = \App\Services\Product\Product::getImagePath($product);
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

        return view('product.index', compact('product','productName', 'productText', 'imagePath', 'imagePathFull', 'productPhotos', 'productVideo', 'productPDF', 'price', 'basePrice',

            'wishlists', 'orders', 'limit1', 'limit2', 'storage_prices','storage_raw_prices'));
    }

    public function search(Request $request){
        info($request->name);
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
