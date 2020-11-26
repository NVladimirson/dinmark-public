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
use Artesaos\SEOTools\Facades\SEOTools;
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
		//dd($orders);
		$curentWishlist = LikeGroup::find(session('current_catalog'));
		//dd($curentWishlist);
		return view('product.wishlist',compact('wishlists','orders', 'curentWishlist'));
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

		session(['current_catalog' => $group->id]);
		$products = Product::with(['storages','holdingArticles','content'])->whereHas('likes',function($likes) use ($group){
			$likes->where([
				['alias',8],
				['group_id',$group->group_id],
				['user',$group->user_id],
			]);
		});
		$holdingId = auth()->user()->getCompany->holding;
		$ids = null;
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
            ->addColumn('name_article_html', function (Product $product){
                $name = \App\Services\Product\Product::getName($product);
                return '<a class="data-product_name" href="'
                    .route('products.show',[$product->id]).'">'.$name.'</a><br>'.
                    '<a href="'.route('products.show',[$product->id]).'">'.$product->article_show.'</a>';
            })
//			->addColumn('name_html', function (Product $product){
//				$name = \App\Services\Product\Product::getName($product);
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
			->addColumn('user_price', function (Product $product) {
                if(\App\Services\Product\Product::hasAmount($product->storages))
                {
                    return \App\Services\Product\Product::getPrice($product);
                }
                return number_format(0,2,'.',' ');
			})
			->addColumn('catalog_price', function (Product $product) use($group){
				$coef = 1;
				if($group->price){
					$coef = $group->price->koef;
				}
                if(\App\Services\Product\Product::hasAmount($product->storages)){
                    return \App\Services\Product\Product::getPriceWithCoef($product,$coef);
                }
                return number_format(0,2,'.',' ');
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
			->rawColumns(['name_article_html','image_html','check_html','actions','article_holding','storage_html'])
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
