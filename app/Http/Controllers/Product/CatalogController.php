<?php

namespace App\Http\Controllers\Product;

use App\Exports\CatalogExport;
use App\Http\Controllers\Controller;
use App\Imports\CatalogImport;
use App\Models\Product\CompanyProductArticle;
use App\Models\Product\Product;
use App\Models\Wishlist\Like;
use Illuminate\Support\Str;
use App\Models\Wishlist\LikeGroup;
use App\Services\Order\OrderServices;
use Illuminate\Http\Request;
use App\Services\Product\CatalogServices;
use App\Services\Product\Product as ProductServices;
use Artesaos\SEOTools\Facades\SEOTools;
use App\Services\Product\CategoryServices;
use Excel;

class CatalogController extends Controller
{
	public function index(){
		SEOTools::setTitle(trans('wishlist.page_list'));
		$wishlists = CatalogServices::getByCompany();
		//dd($wishlists);
		if(!session()->has('current_catalog')){
			$group = LikeGroup::where([
				['user_id',auth()->user()->id],
				['is_main',1]
			])->first();
			session(['current_catalog' => $group->id]);
		}
		$orders = OrderServices::getByCompany();
		$curentWishlist = LikeGroup::find(session('current_catalog'));
		$filters = CategoryServices::getOptionFilters();
		$dinmark_url = \Config::get('values.dinmarkurl');
		return view('product.wishlist',compact('wishlists','orders', 'curentWishlist','filters','dinmark_url'));
	}

    public function addToCatalog($id = 0, Request $request){
        $products = explode( ',', $request->product_id );
				$group = LikeGroup::find($id);
				if($id == 0){
						$last = LikeGroup::whereHas('user',function ($users){
								$users->where('company',auth()->user()->company);
						})->orderBy('id','desc')->first();

						$group = LikeGroup::create([
								'name' => $request->new_wishlist_name,
								'is_main' => 0,
								'user_id' => auth()->user()->id,
								'group_id' => $last->id+1
						]);
				}


        foreach ($products as $product){
          Like::updateOrCreate([
								// 'id' => $group->id,
                'user' => $group->user_id,
                'alias' => 8,
                'content' =>$product,
                'group_id' => $group->group_id,
            ],[
                'status' => 1
            ]);
        }


    	return 'ok';
	}




    public function changeCatalog($id, Request $request){
		$group = LikeGroup::find($id);
        if($id == 0){
            $last = LikeGroup::whereHas('user',function ($users){
                $users->where('company',auth()->user()->company);
            })->orderBy('id','desc')->first();

            $group = LikeGroup::create([
                'name' => $request->new_wishlist_name,
                'is_main' => 0,
                'user_id' => auth()->user()->id,
                'group_id' => $last->id+1
            ]);
        }

		$oldGroup = LikeGroup::find($request->old_catalog_id);

		$likeProduct = Like::where([
		    ['content' ,$request->product_id],
		    ['group_id' ,$oldGroup->group_id],
        ])->first();

        $likeProduct->group_id = $group->group_id;
        $likeProduct->save();

    	return 'ok';
	}

    public function removeToCatalog($id, Request $request){
		$group = LikeGroup::find($id);
		Like::where([
			['content',$request->product_id],
			['group_id',$group->group_id],
		])->delete();

    	return 'ok';
	}

	public function import(Request $request)
	{
		$validatedData = $request->validate([
			'import' => 'required|mimes:xls,xlsx,csv'
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}
		$group = LikeGroup::find(session('current_catalog'));
		$count = Product::with(['storages','holdingArticles'])->whereHas('likes',function($likes) use ($group){
			$likes->where([
				['alias',8],
				['group_id',$group->group_id],
				['user',$group->user_id],
			]);
		})->count();

		Excel::import(new CatalogImport(), request()->file('import'));

		$count = Product::with(['storages','holdingArticles'])->whereHas('likes',function($likes) use ($group){
			$likes->where([
				['alias',8],
				['group_id',$group->group_id],
				['user',$group->user_id],
			]);
		})->count() - $count;
		return redirect()->back()->with('status', $count.trans('wishlist.import_success'));
	}

	public function downloadPrice($id){
        $group = LikeGroup::find($id);
        $excel = new CatalogExport($group);
        return $excel->download('price_'.time().'.xlsx');
    }

	public function changeArticle($id, Request $request){
		$holdingId = auth()->user()->getCompany->holding;
		$article = CompanyProductArticle::where([
			['holding_id', $holdingId],
			['article', $request->article]
		])->first();

		if($article){
			return trans('wishlist.article_already_exists');
		}else{
			CompanyProductArticle::updateOrCreate([
				'product_id' => $id,
				'holding_id' => $holdingId,
			],[
				'article' => $request->article
			]);

			return 'ok';
		}
	}

	public function allAjax(Request $request){
		$group = LikeGroup::with(['price'])->find($request->group);
		info($request->group);
		session(['current_catalog' => $group->id]);
		
		// $products = Product::whereHas('likes',function($likes) use ($group){
		// 	$likes->where([
		// 		['user',$group->user_id],
		// 		['group_id',$group->group_id],
		// 		['alias',8],
		// 	]);
		// });
		$product_ids = \App\Models\Wishlist\Like::where([['user',$group->user_id],['group_id',$group->group_id],['alias',8]])->pluck('content');
		$products = Product::whereIn('id',$product_ids);

		$holdingId = auth()->user()->getCompany->holding;
		 $ids = null;
		// if($request->has('search')){
		// 	$ids = ProductServices::getIdsSearch(request('search')['value']);
		// }
		// $search_article = null;
		//
		// if($request->has('search')){
		// 		$search_article = request('search')['value'];
		// }
		if($request->filter_with_options){
				$language = CategoryServices::getLang();
				$request_options = explode(',',$request->filter_with_options);;
				foreach ($request_options as $key => $request_option) {
					if($key == 0){
						$products = $products->whereHas('options', function($options) use ($request_option,$language){
							$options->whereHas('val_translates', function($option_name) use ($request_option,$language){
								$option_name->where('value',$request_option)->where('language','uk');
							});
						});
					}
					else{
						$products = $products->orwhereHas('options', function($options) use ($request_option,$language){
							$options->whereHas('val_translates', function($option_name) use ($request_option,$language){
								$option_name->where('value',$request_option)->where('language','uk');
							});
					});
				}
				}
		}

		return datatables()
			->eloquent($products)
			->addColumn('image_html', function (Product $product) {
				$src = ProductServices::getImagePath($product);

				return '<img src="'.$src.'" width="80">';
			})
      ->addColumn('name_article_html', function (Product $product){
         $name = ProductServices::getName($product);
            return '<a class="data-product_name" href="'
                    .route('products.show',[$product->id]).'">'.$name.'</a><br>'.
                    // '<a href="'.route('products.show',[$product->id]).'">'.$product->article_show.'</a>';
                    '<span>'.$product->article_show.'</span>';
            })
//			->addColumn('name_html', function (Product $product){
//				$name = ProductServices::getName($product);
//				return '<a href="'.route('products.show',[$product->id]).'">'.$name.'</a>';
//			})
//			->addColumn('article_show_html', function (Product $product) {
//				return '<a href="'.route('products.show',[$product->id]).'">'.$product->article_show.'</a>';
//			})

			->addColumn('article_holding', function (Product $product) use ($holdingId) {
				$article = '';

				if($product->holdingArticles->firstWhere('holding_id',$holdingId)){
					$article = $product->holdingArticles->firstWhere('holding_id',$holdingId)->article;
				}
				return view('product.include.holding_article', compact('product','article'));
			})
			// ->addColumn('user_price', function (Product $product) {
      //           if(ProductServices::hasAmount($product->storages))
      //           {
      //               return '<div id="catalog_user_price_'.$product->id.'">'.ProductServices::getPrice($product).'</div>';
      //           }
      //           return number_format(0,2,'.',' ');
			// })
			// ->addColumn('catalog_price', function (Product $product) use($group){
			// 	$coef = 1;
			// 	if($group->price){
			// 		$coef = $group->price->koef;
			// 	}
      //           if(ProductServices::hasAmount($product->storages)){
      //               return '<div id="catalog_catalog_price_'.$product->id.'">'.ProductServices::getPriceWithCoef($product,$coef).'</div>';
      //           }
      //           return number_format(0,2,'.',' ');
			// })
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
								$emptystorages = true;
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
                $name = ProductServices::getName($product);

                return view('product.include.wishlist_action_buttons',compact('product','storage', 'name', 'hasStorage'));
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
				// if($ids){
				// 	$product->whereIn('id',$ids);
				// }else{
				// 	$product->select();
				// }
				$product->select();
			})
			->filter(function ($product) use ($request,$ids) {
				if (request()->has('storage_html')) {
					$product->whereHas('storage_1', 'like',"%" . request('storage_html') . "%")->orWhere()->whereHas('termin', 'like',"%" . request('storage_html') . "%");
				}
				if (request()->has('article_show_html')) {
					$product->whereHas('article_show', 'like',"%" . request('article_show_html') . "%");
				}
				// if(request()->has('name_html')){
				// 	$product->whereIn('id',$ids);
				// }
			}, true)
			->rawColumns([
				'name_article_html',
				'image_html',
				'check_html',
				'actions',
				'article_holding',
				'storage_html',
				// 'user_price',
				// 'catalog_price',
				'retail_user_prices',
				'html_limit_1',
				'html_limit_2',
				'calc_quantity',
				'sum_w_taxes',
				'package_weight'
			])
			->toJson();
	}

	public function store(Request $request){
		$validatedData = $request->validate([
			'name'		=> 'required',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}

		$last = LikeGroup::whereHas('user',function ($users){
			$users->where('company',auth()->user()->company);
		})->orderBy('id','desc')->first();

		LikeGroup::create([
			'name' => $validatedData['name'],
			'is_main' => 0,
			'user_id' => auth()->user()->id,
			'group_id' => $last->id+1
		]);

		return redirect()->back()->with('status', trans('wishlist.modal_new_success'));
	}

	public function update(Request $request, $id){
		$validatedData = $request->validate([
			'rename'		=> 'required',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}

		$likeGroup = LikeGroup::find($id);
		$likeGroup->name = $validatedData['rename'];
		$likeGroup->save();

		return redirect()->back()->with('status', trans('wishlist.modal_rename_success'));
	}


	public function setPrice($id, Request $request){
		$likeGroup = LikeGroup::find($id);
		$likeGroup->price_id = $request->price != ''?$request->price : null;
		$likeGroup->save();

		return redirect()->back()->with('status', trans('wishlist.modal_price_success'));
	}

	public function changeStorage(Request $request){
		$product_storage_id = $request->storage_id;
		$product_info = Product::with('storages')->find($request->product_id);
		$storage_id = $product_info->storages->where('storage_id',$product_storage_id)->first()->id;
		return ProductServices::getPrice($product_info,$storage_id);
	}

	public function destroy(Request $request, $id){


		$likeGroup = LikeGroup::find($id);
		Like::where([
			['group_id',$likeGroup->group_id],
			['user',$likeGroup->user_id],
		])->delete();
		$likeGroup->delete();
		$likeGroup = LikeGroup::where([
			['is_main', 1],
			['user_id',auth()->user()->id]
		])->first();

		session(['current_catalog' => $likeGroup->id]);
		return redirect()->back()->with('status', trans('wishlist.modal_delete_success'));
	}
}
