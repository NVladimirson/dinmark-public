<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
}
