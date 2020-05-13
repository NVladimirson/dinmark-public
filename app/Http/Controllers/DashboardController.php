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
				$users->where('company',auth()->user()->company)
					->orderBy('id','desc');
			})->get()->groupBy(function($val) {
				return Carbon::parse($val->date_add)->format('m Y');
			});

		//dd($orders);

		SEOTools::setTitle(trans('dashboard.page_name'));
		return view('dashboard',compact('orders'));
    }
}
