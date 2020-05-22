<?php

namespace App\Http\Controllers;

use App\Models\Order\Order;
use Artesaos\SEOTools\Facades\SEOTools;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public function index()
	{
		$orders = Order::with(['products.product'])
			->whereHas('getUser', function ($users){
				$users->where('company',session('current_company_id'))
					->orderBy('id','desc');
			})->get()->groupBy(function($val) {
				return Carbon::parse($val->date_add)->format('m Y');
			});

		$ordersWithoutRequest = Order::with(['products.product'])
			->whereHas('getUser', function ($users){
				$users->where('company',session('current_company_id'))
					->orderBy('id','desc');
			})
			->where('status','<>',8)
			->get();
		$ordersSuccess = Order::with(['products.product'])
			->whereHas('getUser', function ($users){
				$users->where('company',session('current_company_id'))
					->orderBy('id','desc');
			})
			->where([
				['status','<>',8],
				['status','<>',1],
				['status','<>',7],
			])
			->get();
		$order_counts = $ordersWithoutRequest->count();
		$success_procent = $ordersSuccess->count() / $order_counts * 100;
		$success_total = $ordersSuccess->sum('total');

		$success_weight = 0;
		foreach ($ordersSuccess as $orderSuccess){
			foreach ($orderSuccess->products as $orderProduct){
				$success_weight += ($orderProduct->product->weight/100) * $orderProduct->quantity;
			}
		}
		//dd($orders);

		SEOTools::setTitle(trans('dashboard.page_name'));
		return view('dashboard',compact('order_counts', 'success_procent', 'success_total', 'success_weight','orders'));
    }
}
