<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\OrderStatus;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

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
		$price = \App\Services\Product\Product::calcPrice($product)/100;
		$total = $price * $request->quantity;

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
			'price' => $total,
			'price_in' => $product->price,
			'quantity' => $request->quantity,
			'quantity_wont' => $request->quantity,
			'date' => Carbon::now()->timestamp,
		]);

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
}
