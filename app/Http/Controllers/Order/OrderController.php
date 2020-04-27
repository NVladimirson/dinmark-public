<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Imports\OrderImport;
use App\Models\Company\Company;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\OrderStatus;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Excel;

class OrderController extends Controller
{
	public function index(){
		SEOTools::setTitle(trans('order.page_list'));
		$statuses = OrderStatus::all();
		return view('order.index',compact('statuses'));
	}

	public function addToOrder($id, Request $request){
		$order = null;
		$product = Product::with(['storages'])->find($request->product_id);
		$price = abs(\App\Services\Product\Product::calcPrice($product)/(float)100);
		$total = round($price * $request->quantity,2);
		if($id == 0){
			$order = Order::create([
				'user' => auth()->user()->id,
				'status' => 8,
				'total' => $total,
				'source' => 'b2b',
			]);
		}else{
			$order = Order::find($id);
			$order->total += $total;
			$order->save();
		}

		OrderProduct::create([
			'cart' => $order->id,
			'user' => auth()->user()->id,
			'active' => 1,
			'product_alias' => $product->wl_alias,
			'product_id' => $product->id,
			'storage_alias' => $request->storage_id,
			'price' => $price,
			'price_in' => $product->price,
			'quantity' => $request->quantity,
			'quantity_wont' => $request->quantity,
			'date' => Carbon::now()->timestamp,
		]);

		return 'ok';
	}

	public function removeOfOrder($id){
		$orderProduct = OrderProduct::with(['getCart'])->find($id);

		$orderProduct->getCart->total -= round($orderProduct->price*$orderProduct->quantity, 2);
		$orderProduct->getCart->save();
		$orderProduct->delete();

		return 'ok';
	}

	public function allAjax(Request $request){
		$orders = Order::whereHas('getUser', function ($users){
			$users->where('company',auth()->user()->company);
		});

		if($request->has('status_id')){
			$orders->where('status',$request->status_id);
		}

		return datatables()
			->eloquent($orders)
			->addColumn('number_html', function (Order $order) {
				$number = $order->id;
				if($order->public_number){
					$number = $order->public_number;
				}
				return $number;
			})
			->addColumn('date_html', function (Order $order) {
				$date = Carbon::parse($order->date)->format('d.m.Y h:i');
				return $date;
			})
			->addColumn('status_html', function (Order $order) {
				return $order->getStatus->name;
			})
			->addColumn('payment_html', function (Order $order) {
				return $order->getStatus->name;
			})
			->addColumn('total_html', function (Order $order) {
				return number_format($order->total,2,'.',' ');
			})
			->addColumn('customer', function (Order $order) {
				return $order->getUser->name;
			})
			->addColumn('author', function (Order $order) {
				return $order->getUser->name;
			})
			->addColumn('actions', function (Order $order) {
				return view('order.include.action_buttons',compact('order'));
			})
			->rawColumns(['name_html','article_show_html','image_html','check_html','actions','article_holding'])
			->toJson();
	}

	public function create(){
		SEOTools::setTitle(trans('order.page_create'));
		$order = Order::firstOrCreate([
			'user' => auth()->user()->id,
			'status' => 8,
			'total' => 0,
			'source' => 'b2b',
		]);

		$companies = Company::with(['users'])
		->where([
			['holding',auth()->user()->getCompany->holding],
			['holding','<>',0]
		])->orWhere('id',auth()->user()->company)->get();


		return view('order.create',compact('order', 'companies'));
	}

	public function show($id){
		session()->forget('not_founds');
		session()->forget('not_available');

		$order = Order::with(['products.product'])->find($id);
		SEOTools::setTitle(trans('order.page_update').$order->id);
		$companies = Company::with(['users'])
			->where([
				['holding',auth()->user()->getCompany->holding],
				['holding','<>',0]
			])->orWhere('id',auth()->user()->company)->get();

		$products = [];
		$koef = $order->is_pdv?1.2:1;

		foreach($order->products as $orderProduct){
			$price = \App\Services\Product\Product::calcPrice($orderProduct->product)/100 * $koef;

			$total = $price * $orderProduct->quantity;

			$products[] = [
				'id'	=> $orderProduct->id,
				'name' => \App\Services\Product\Product::getName($orderProduct->product),
				'quantity' => $orderProduct->quantity,
				'price' => number_format($price*100,2,'.', ' '),
				'total' => number_format($total,2,'.', ' '),
			];
		}

		if($order->status == 8){
			return view('order.show',compact('order', 'companies', 'products', 'koef'));
		}else{
			return view('order.show_order',compact('order', 'companies', 'products', 'koef'));
		}

	}

	public function update($id, Request $request){
		session()->forget('not_founds');
		session()->forget('not_available');

		$order = Order::with(['products.product'])->find($id);
		$order->sender_id = $request->sender_id;
		$order->user = $request->customer_id;
		$order->comment = $request->comment;
		$order->is_pdv = $request->has('is_pdv');
		$order->save();

		if($request->submit == 'add_product'){
			$this->addToOrder($id, $request);
		}

		if($request->submit == 'import_product'){
			$validatedData = $request->validate([
				'import' => 'required|mimes:xls,xlsx,csv'
			]);

			if(!is_array($validatedData) ){
				if($validatedData->fails()) {
					return Redirect::back()->withErrors($validatedData);
				}
			}

			Excel::import(new OrderImport($order), request()->file('import'));

		}

		if($request->submit == 'order'){
			$order->status = 1;
		}


		$order->save();

		return redirect()->route('orders.show',$id);
	}
}
