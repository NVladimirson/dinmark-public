<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;


use App\Jobs\ProductOptionFiltersJob;
use App\Models\Content;
use App\Models\Order\Implementation;
use App\Models\Order\ImplementationProduct;
use App\Models\Order\Order;
use App\User;
use App\Models\Company\Company;
use App\Models\Company\CompanyPrice;
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
use App\Models\Wishlist\LikeGroup;

class ProductController extends Controller
{


    public function index(){
        SEOTools::setTitle(trans('product.all_tab_name'));
        $wishlists = CatalogServices::getByCompany();
        $orders = OrderServices::getByCompany();
        $terms = CategoryServices::getTermsForSelect();
        $filters = CategoryServices::getOptionFilters();
        $dinmark_url = \Config::get('values.dinmarkurl');
        return view('product.all',compact('wishlists', 'orders', 'terms','filters','dinmark_url'));
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
      $this->dispatch(new ProductOptionFiltersJob());
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

        if(isset($request->term)){
            $terms = explode(',',$request->term);
            $products = $products->whereHas('storages', function($storages) use($terms){
                $storages->where('amount','>',0)->whereHas('storage',function($storage) use($terms){
                    $storage->whereIn('term',$terms);
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
            $order_products = \DB::select('
            SELECT s_cart_products.product_id AS `product_id`,COUNT(*)
            FROM s_cart
            INNER JOIN s_cart_products ON s_cart.id = s_cart_products.cart
            WHERE `status` NOT IN (6,7,8)
            GROUP BY `product_id`
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
            $ids = ProductServices::getIdsSearch(request('search')['value']);
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
            $src = ProductServices::getImagePath($product);

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

            return '<div class="product-image"><img src="'.$src.'" alt="https://dinmark.com.ua/images/dinmark_nophoto.jpg" width="60">
                        <div class="wrap-label">
                        '.$spans.'
                        </div>
                    </div>';
        })
        ->addColumn('name_article_html', function (Product $product){
            $name = ProductServices::getName($product);
            return '<a class="data-product_name" href="'
            .route('products.show',[$product->id]).'">'.$name.'</a><br>'.
            '<span>'.$product->article_show.'</span>';
        })
            ->addColumn('retail_user_prices', function (Product $product) {
                if(ProductServices::hasAmount($product->storages)){
                    $storage = $product->storages->first();
                    $package = $storage->package;
                    $retail = ProductServices::getBasePrice($product,$storage->storage_id);
                    $user_price = ProductServices::getPrice($product,$storage->id);
                    $old_price = ProductServices::getOldPrice($product,$storage->storage_id);
                    // <span style="color:#f0c674">
                    if($product->old_price){
                      return '<p id="retail_user_price_'.$product->id.'" style="margin-bottom:0px">
                      <span>'.__('product.table_header_price_retail').': </span>
                      <span class="retail_price">'.$retail.'</span>
                      <br>
                      <span>'.__('product.table_header_price').': </span>
                      <span class="old_price" style="color:red"><strike>'.$old_price.'</strike></span>
                      <span class="user_price">'. $user_price .'</span></p>';
                    }else{
                      return '<p id="retail_user_price_'.$product->id.'" style="margin-bottom:0px">
                      <span>'.__('product.table_header_price_retail').': </span>
                      <span class="retail_price">'.$retail.'</span>
                      <br>
                      <span>'.__('product.table_header_price').': </span>
                      <span class="old_price" style="display:none;color:red"><strike>'.$old_price.'</strike></span>
                      <span class="user_price">'. $user_price .'</span></p>';
                    }


                }
                return number_format(0,2,'.',' ');
            })
            ->addColumn('html_limit_1', function (Product $product) {
                $storage = $product->storages->first();
                if(isset($storage->limit_1) && $storage->limit_1!=0){
                    //$price_limit = ProductServices::getPriceWithCoef($product,0.97);
                    $price_limit = number_format(ProductServices::getPriceWithCoefUnformatted($product,$storage->id,0.97),2,'.',' ');
                    $limit = $storage->limit_1;
                    return '<p id="limit_1_'.$product->id.'" style="color: #96ca0a;margin-bottom: 0px" ><span class="limit_amount_price_1">'.$price_limit.
                        '</span><br><span class="limit_amount_quantity_1">'.'>'.$limit.'</span></p>';
                }
                else{
                    return '<p id="limit_1_'.$product->id.'" style="color: #96ca0a;margin-bottom: 0px" ><span class="limit_amount_price_1"> -
                        </span><br><span class="limit_amount_quantity_1"></span></p>';
                }
            })
            ->addColumn('html_limit_2', function (Product $product) {
                $storage = $product->storages->first();
                if(isset($storage->limit_2) && $storage->limit_2!=0){
                    //$price_limit = ProductServices::getPriceWithCoef($product,0.93);
                    $price_limit = number_format(ProductServices::getPriceWithCoefUnformatted($product,$storage->id,0.93),2,'.',' ');
                    $limit = $storage->limit_2;
                    return '<p id="limit_2_'.$product->id.'" style="color: #f0c674;margin-bottom: 0px" ><span class="limit_amount_price_2">'.$price_limit.
                        '</span><br><span class="limit_amount_quantity_2">'.'>'.$limit.'</span></p>';
                }
                else{
                    return '<p id="limit_2_'.$product->id.'" style="color: #f0c674;margin-bottom: 0px" ><span class="limit_amount_price_2"> -
                        </span><br><span class="limit_amount_quantity_2"></span></p>';
                }

            })
            ->addColumn('storage_html', function (Product $product) {
                $value = trans('product.storage_empty');
                $emptyvalue = trans('product.storage_choose');
                $emptystorages = true;
                if($product->storages){
                    $storages = $product->storages;
                    if(count($storages)){
                        $value = '<select onchange="initCalc(this)" class="custom-select" product_id="'.$product->id.'" id="storage_product_'.$product->id.'">';
                        if(isset($product->storages->firstWhere('is_main',1)->storage_id)){
                            $main_storage = $product->storages->firstWhere('is_main',1)->storage_id;
                        }
                        else{
                            $main_storage = 0;
                        }
                        foreach ($storages as $key => $storage) {
                            if($storage->amount!=0){
                              $emptystorages = false;
                              $term = $storage->storage->term;
                              $days = ProductServices::getStingDays($term);
                              // $name = CatalogServices::dayrounder($storage->amount).
                              // ' / '.$term.' '.$days.' ('.$storage->storage->name.')';
                              $name = __('product.storage_name'). ' '. $storage->storage->term . ' '. __('product.storage_term_measure_shortly').
                              ' / '.CatalogServices::dayrounder($storage->amount) . ' шт.';
                              $value .= '<option value="'.$storage->storage->id.'" package_min="'.$storage->package.'"
                              package_max="'.$storage->amount.'"';
                              if($storage->storage->id == $main_storage){
                                  $value .= 'selected>'.$name.'</option>';
                              }
                              else{
                                  $value .= '>'.$name.'</option>';
                              }
                            }
                        }
                        $value .= '</select>';
                    }
                    $value .= '</select>';
                }
                if($emptystorages){
                  $value = trans('product.storage_empty');
                }
                return $value;
            })
            ->addColumn('calc_quantity', function (Product $product) {
                $storage = $product->storages->firstWhere('is_main',1);

                if(ProductServices::hasAmount($product->storages)){
                  if(isset($storage->package)){
                    $package = $storage->package;
                  }else{
                    $package = 1;
                  }
                    // return '
                    // <input id="calc_quantity_'.$product->id.'" onchange="changeamount(this)" type="number"
                    // name="quantity" class="form-control m-b-15" style="max-width: 80px;margin-bottom: 0px!important;"
                    // value="'.$storage->package.'" min="'.$storage->package.'" step="'.$storage->package.'" datamax="'.$storage->amount.'"/>';

                    return '
                    <input id="calc_quantity_'.$product->id.'" onchange="changeamount(this)" type="number"
                    name="quantity" class="form-control m-b-15" style="max-width: 100px;margin-bottom: 0px!important;"
                    value="0" min="0" step="'.$storage->package.'" datamax="'.$storage->amount.'"/>';
                }
                else{
                    // return '
                    // <input id="calc_quantity_'.$product->id.'" onchange="changeamount(this)" type="number"
                    // name="quantity" class="form-control m-b-15" style="max-width: 80px;margin-bottom: 0px!important; display:none"
                    // value="0" min="0" step="10" data-max="1000"/>';
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
                    return '<div>
                <p id="package_weight_'.$product->id.'" style="margin-bottom: 0px;">
                <span class="multiplier">0</span>
                <span class="x">x</span>
                <span class="package">'.$package.'</span>
                <br>
                <span class="weight">'.number_format(0,3,'.',',').'</span>
                </p></div>';
                }else{
                    return '<div>
                <p id="package_weight_'.$product->id.'" style="margin-bottom: 0px;">
                <span class="multiplier"></span>
                <span class="x" style="display:none">x</span>
                <span class="package"></span>
                <span class="weight"></span>
                </p></div>';
                }
            })
            ->addColumn('sum_w_taxes', function (Product $product) {
                $storage = $product->storages->first();
                if(isset($storage)){
                  $package = $storage->package;
                  $price = ProductServices::getPriceUnformatted($product,$storage->id);
                  $price = $price/100 * $package;
                  if(isset($storage->limit_2) && $storage->limit_2!=0){
                    return '<div><p id="sum_w_taxes_'.$product->id.'" style="margin-bottom: 0px"><span class="price">'.number_format(0,2,'.',' ').'</span> <br>
                      <span class="discount">-0%</span> <span class="discountamount">'.number_format(0,2,'.',' ').'</span> </p></div>';
                  }else{
                    return '<div><p id="sum_w_taxes_'.$product->id.'" style="margin-bottom: 0px"><span class="price">'.number_format(0,2,'.',' ').'</span> <br>
                      <span class="discount" style="display:none">-0%</span> <span class="discountamount" style="display:none">'.number_format(0,2,'.',' ').'</span> </p></div>';
                    // return '<div><p id="sum_w_taxes_'.$product->id.'" style="margin-bottom: 0px"><span class="price">'.number_format(0,2,'.',' ').'</span> <br>
                    //   <span class="discount"></span> <span class="discountamount"></span> </p></div>';
                  }
                }else{
                    return '<div><p id="sum_w_taxes_'.$product->id.'" style="margin-bottom: 0px"><span class="price"></span> <br>
                      <span class="discount"></span> <span class="discountamount"></span> </p></div>';
                }

            })
            ->addColumn('actions', function (Product $product) {
                $storage = $product->storages->firstWhere('is_main',1);
                $hasStorage = ProductServices::hasAmount($product->storages);
                $src = ProductServices::getImagePath($product);
                $name = ProductServices::getName($product);
                return view('product.include.action_buttons',compact('product','hasStorage','name','src','storage'));
            })
            // ->orderColumn('name_article_html',false)
            ->orderColumn('storage_html','storage_1 $1')
            ->orderColumn('article_show_html','article_show $1')
            // ->orderColumn('user_price', function ($product, $order){
            //         ->leftJoin('s_currency', 's_shopshowcase_products.currency', '=', 's_currency.code')
            //         //->select('s_shopshowcase_products.*', \DB::raw('s_shopshowcase_products.price * s_currency.currency AS price_with_currency'))
            //         ->select('s_shopstorage_products.*', \DB::raw('s_shopstorage_products.price * s_currency.currency AS price_with_currency'))
            //         ->orderBy("price_with_currency", $order);
            // })
            ->filterColumn('storage_html', function($product, $keyword) {
                $product->where('storage_1', 'like',["%{$keyword}%"])->orWhere('termin', 'like',["%{$keyword}%"]);
            })
            ->filterColumn('article_show_html', function($product, $keyword) {
                $product->where('article_show', 'like',["%{$keyword}%"]);
            })
            ->filterColumn('name_article_html', function($product, $keyword) use($ids) {
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
                if(request()->has('name_article_html')){
                    $product->whereIn('id',$ids);
                }
            }, true)
            ->rawColumns(['name_article_html','html_limit_1','html_limit_2','image_html','check_html','actions','switch','storage_html','calc_quantity','sum_w_taxes','package_weight','retail_price','user_price','retail_user_prices'])
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

        $retail = ProductServices::getBasePrice($productinfo,$storageinfo->storage_id);
        $pricefor100 = ProductServices::getPriceUnformatted($productinfo,$storageinfo->id);
        //$oldprice = ProductServices::getOldPrice($productinfo,$storageinfo->storage_id);
        $multiplier = $amount/$package - $amount%$package;


        if (($amount >= $seven_percent_discount_limit) && $seven_percent_discount_limit){
            $price = ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.93);
            $discount = '7%';
            $user_price = $pricefor100*0.93;
        }
        else if(($amount >= $three_percent_discount_limit) && $three_percent_discount_limit){
            $price = ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.97);
            $discount = '3%';
            $user_price = $pricefor100*0.97;
        }
        else{
            $price = ProductServices::getPriceUnformatted($productinfo,$storageinfo->id);
            $discount = '0%';
            $user_price = $pricefor100;
        }


        //$discountamount = intdiv($amount,100)*($pricefor100 - $price);
        $discountamount = ($pricefor100-$price)*($amount/100);
        $price = $price/100*$amount;
        $unit = $productinfo->unit;
        preg_match_all('!\d+!', $unit, $isnumber);
        if(!empty($isnumber[0])){
            $unitnumber = $isnumber[0][0];
        }else{
            $unitnumber = 1;
        }//сори

        $weight = $productinfo->weight * ($amount/$unitnumber);
        //number_format($weight,3,'.',' ')

        //$retail_price = number_format(ProductServices::getBasePriceUnformatted($productinfo,$storage_id)*$multiplier,2,'.',' ');

        //$user_price = number_format(ProductServices::getPriceUnformatted($productinfo,$storage_id)*$multiplier,2,'.',' ');

        $response = [
            'name' => $name,
            //'retail_price' => $retail_price,
            //'user_price' => $user_price,
            'multiplier' => $multiplier,
            'storageamount' => $storageamount,
            'package' => $package,
            'weight' => number_format($weight,3,'.',' '),
            'price' => number_format($price,2,'.',' '),
            'discount' => $discount,
            'discountamount' => number_format($discountamount,2,'.',' '),
            //'discountamount' => number_format($multiplier*ProductServices::getPriceUnformatted($productinfo,$storageinfo->id) - $multiplier*$price,2,'.',' '),
            'limit_amount_price_1' => number_format(ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.97),2,'.',' '),
            'limit_amount_price_2' => number_format(ProductServices::getPriceWithCoefUnformatted($productinfo,$storageinfo->id,0.93),2,'.',' '),
            'limit_amount_quantity_1' => $three_percent_discount_limit,
            'limit_amount_quantity_2' => $seven_percent_discount_limit,
            'user_price' => number_format($user_price,2,'.',' '),
            'retail' => $retail,
            'price100' => number_format($pricefor100,2,'.',' '),
            //'oldprice' => $oldprice
        ];

        return $response;
    }

    public function show($id){
        $product = Product::find($id);
        $productName = ProductServices::getName($product);
        $productText = ProductServices::getText($product);
        $imagePath = ProductServices::getImagePathThumb($product);
        $imagePathFull = ProductServices::getImagePath($product);
        $productPhotos = ProductServices::getImagePathThumbs($product);
        $productVideo = ProductServices::getVideo($product);
        $productPDF = ProductServices::getPDF($product);
        //$priceproduct = ProductShopStorage::find($id);
        $price = ProductServices::getPrice($product);
        $limit1 = ($product->limit_1 > 0)? (ProductServices::getPriceWithCoef($product,0.97).' '.trans('product.table_header_price_from',['quantity' => $product->limit_1])) : '-';
        $limit2 = ($product->limit_2 > 0)? (ProductServices::getPriceWithCoef($product,0.93).' '.trans('product.table_header_price_from',['quantity' => $product->limit_2])) : '-';

        $orders = OrderServices::getByCompany();
        $basePrice = ProductServices::getBasePrice($product);

        $wishlists = CatalogServices::getByCompany();

        $storage_prices = [];
        $storage_raw_prices = [];

        foreach ($product->storages as $storage){
            $storage_prices[$storage->id] = ProductServices::getPrice($product,$storage->id);
            $storage_raw_prices[$storage->id] = ProductServices::calcPrice($product,$storage->id);
        }
        SEOTools::setTitle($productName);
        //dd($basePrice);
        return view('product.index', compact('product','productName', 'productText', 'imagePath', 'imagePathFull', 'productPhotos', 'productVideo', 'productPDF', 'price', 'basePrice',

            'wishlists', 'orders', 'limit1', 'limit2', 'storage_prices','storage_raw_prices'));
    }

    public function search(Request $request){
        $search = $request->name;
        $formatted_data = [];

        $ids = ProductServices::getIdsSearch($search);

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
            $name = ProductServices::getName($product);
            $storage = $product->storages->firstWhere('is_main',1);
            $min = 0;
            $max = 0;
            $storage_id = 1;
            if($storage){
                $min = $storage->package;
                $max = $storage->amount;
                $storage_id = $storage->storage_id;
            }
            else{
              continue;
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

        $ids = ProductServices::getIdsSearch($search);

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
            $name = ProductServices::getName($product);

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
        ProductServices::getPriceRequest($id, $request->quantity, $data);

        return 'ok';
    }
}
