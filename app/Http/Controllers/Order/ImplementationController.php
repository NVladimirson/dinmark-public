<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Company\Client;
use App\Models\Order\Implementation;
use App\Models\Order\ImplementationProduct;
use App\Models\Product\Product;
use App\Services\TimeServices;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Carbon;
use PDF;

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
			->addColumn('date',function (Implementation $implementation){

				return Carbon::parse($implementation->date_add)->format('d.m.Y');
			})
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
			->addColumn('btn_pdf',function (Implementation $implementation){

				return '<a href="'.route('implementations.pdf',[$implementation->id]).'" class="btn btn-sm btn-primary">'.trans('implementation.btn_generate_pdf').'</a>';
			})
			->addColumn('products',function (Implementation $implementation){
				$products = [];
				foreach ($implementation->products as $implementationProduct){
					$products[] = [
						'product_id'	=> $implementationProduct->orderProduct->product->id,
						'name'			=> \App\Services\Product\Product::getName($implementationProduct->orderProduct->product),
						'quantity'		=> $implementationProduct->quantity,
						'total'			=> number_format($implementationProduct->total,2,',',' '),
						'order'			=> $implementationProduct->orderProduct->getCart->id,
						'order_number'	=> $implementationProduct->orderProduct->getCart->public_number ?? $implementationProduct->orderProduct->getCart->id,
					];
				}
				return view('order.include.implementation_products',compact(['products']))->render();
			})
			->rawColumns(['products','btn_pdf'])
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
				'product_id'	=> $implementationProduct->orderProduct->product->id,
				'name'	=> \App\Services\Product\Product::getName($implementationProduct->orderProduct->product).'('.$implementationProduct->orderProduct->product->article_show.')',
				//'min'	=> $implementationProduct->quantity,
				'max'	=> $implementationProduct->quantity,
			];
		}

		return \Response::json($formatted_data);
	}

	public function generatePDF($id){
		$implementation = Implementation::with(['products.orderProduct.product','products.orderProduct.getCart'])->find($id);

		$products = [];
		$orderTotal = 0;

		foreach($implementation->products as $implementationProduct){
			$price = ($implementationProduct->total/ $implementationProduct->quantity)/120 * 100;

			$total = $implementationProduct->total/120 * 100;
			$orderTotal += $total;

			$products[] = [
				'id'	=> $implementationProduct->id,
				'name' => \App\Services\Product\Product::getName($implementationProduct->orderProduct->product,'uk'),
				'quantity' => $implementationProduct->quantity/100,
				'package' => 100,
				'price' => number_format($price*100,2,',', ' '),
				'total' => number_format($total,2,',', ' '),
			];
		}
		$user = null;
		$company = null;
		if($implementation->products->first()->orderProduct->getCart->user > 0){
			$user = $implementation->products->first()->orderProduct->getCart->getUser;
			$company = $user->getCompany;
		}else{
			$user = Client::find(-$implementation->products->first()->orderProduct->getCart->user);
			$company = $user->company;
		}



		$pdf = PDF::loadView('order.pdf_expense_invoice', [
			'implementation' => $implementation,
			'date' => TimeServices::getFromTime($implementation->date_add),
			'products' => $products,
			'total' => number_format($orderTotal, 2, ',', ' '),
			'pdv' => number_format($orderTotal*0.2, 2, ',', ' '),
			'totalPdv' => number_format($orderTotal * 1.2, 2, ',', ' '),
			'pdv_text' => \App\Services\Product\Product::getStringPrice($orderTotal*0.2),
			'totalPdv_text' => \App\Services\Product\Product::getStringPrice($orderTotal*1.2),
			'user'	=> $user
		]);
		$pdf->setOption('enable-smart-shrinking', true);
		$pdf->setOption('no-stop-slow-scripts', true);
		return $pdf->download(($company->prefix).'_'.$id.'.pdf');
	}
}
