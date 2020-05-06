<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Implementation;
use App\Models\Order\ImplementationProduct;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class ImplementationController extends Controller
{
    public function index(){
		SEOTools::setTitle(trans('implementation.page_list'));
		return view('order.implementation');
	}

	public function ajax(Request $request)
	{
		$implementations = Implementation::with(['products.orderProduct.product','products.orderProduct.getCart'])
			->whereHas('sender',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['holding', auth()->user()->getCompany->holding],
						['holding', '<>', 0],
					])->orWhere([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})
			->orWhereHas('customer',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['holding', auth()->user()->getCompany->holding],
						['holding', '<>', 0],
					])->orWhere([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})
			->orderBy('id','desc');

		return datatables()
			->eloquent($implementations)
			->addColumn('sender',function (Implementation $implementation){
				if($implementation->sender_id == 0){
					return 'Dinmark';
				}else{
					return $implementation->sender->name;
				}
			})
			->addColumn('customer',function (Implementation $implementation){
				if($implementation->customer_id < 0){
					return 'Клиент';
				}else{
					return $implementation->customer->name;
				}
			})
			->addColumn('total',function (Implementation $implementation){

				return number_format($implementation->products->sum('total'),2,',',' ');
			})
			->addColumn('products',function (Implementation $implementation){
				$products = [];
				foreach ($implementation->products as $implementationProduct){
					$products[] = [
						'name'			=> \App\Services\Product\Product::getName($implementationProduct->orderProduct->product),
						'quantity'		=> $implementationProduct->quantity,
						'total'			=> number_format($implementationProduct->total,2,',',' '),
						'order'			=> $implementationProduct->orderProduct->getCart->id,
						'order_number'	=> $implementationProduct->orderProduct->getCart->public_number ?? $implementationProduct->orderProduct->getCart->id,
					];
				}
				return view('order.include.implementation_products',compact(['products']))->render();
			})
			->rawColumns(['products'])
			->toJson();
	}

	public function find(Request $request)
	{
		$search = $request->name;
		$formatted_data = [];

		$implementations = Implementation::whereHas('sender',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['holding', auth()->user()->getCompany->holding],
						['holding', '<>', 0],
					])->orWhere([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})
			->orWhereHas('customer',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['holding', auth()->user()->getCompany->holding],
						['holding', '<>', 0],
					])->orWhere([
						['id', auth()->user()->getCompany->id],
					]);
				});
			})
			->where(function ($userImplementation) use ($search){
				$userImplementation
					->where('id','like',"%".$search."%")
					->orWhere('public_number','like',"%".$search."%");
			})
			->orderBy('id','desc')
			->limit(10)
			->get();

		foreach ($implementations as $implementation) {
			$formatted_data [] = [
				'id' => $implementation->id,
				'text' => $implementation->id.' ('.$implementation->public_number.')',
			];
		}

		return \Response::json($formatted_data);
	}

	public function getProductsAjax($id)
	{
		$implementationProducts = ImplementationProduct::with(['orderProduct.product'])
			->where('implementation_id',$id)
			->get();

		$formatted_data = [];

		foreach ($implementationProducts as $implementationProduct){
			$formatted_data[] = [
				'id'	=> $implementationProduct->id,
				'name'	=> \App\Services\Product\Product::getName($implementationProduct->orderProduct->product).'('.$implementationProduct->orderProduct->product->article_show.')',
				//'min'	=> $implementationProduct->quantity,
				'max'	=> $implementationProduct->quantity,
			];
		}

		return \Response::json($formatted_data);
	}
}
