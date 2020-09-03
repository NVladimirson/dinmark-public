<?php

namespace App\Http\Controllers;

use App\Models\Order\Order;
use App\Models\Order\Payment;
use App\Models\Ticket\TicketMessage;
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
		$success_procent = 0;
		if($order_counts  != 0){
			$success_procent = $ordersSuccess->count() / $order_counts * 100;
		}

		$success_total = $ordersSuccess->sum('total');

		$success_weight = 0;
		foreach ($ordersSuccess as $orderSuccess){
			foreach ($orderSuccess->products as $orderProduct){
				$success_weight += ($orderProduct->product->weight/100) * $orderProduct->quantity;
			}
		}

		$user = auth()->user();
		$last_orders = Order::where('user', $user->id)
            ->orderBy('date_add','desc')
            ->limit(5)
            ->get();

		$last_payment = Payment::whereHas('order', function ($order) use ($user){
                $order->where('user', $user->id);
            })
            ->orderBy('date_add','desc')
            ->first();

		$last_messages = TicketMessage::whereHas('chat',function ($chat) use ($user){
            $chat->where(function($q){
                $q->where('user_id',auth()->user()->id)
                    ->orWhere('manager_id',auth()->user()->id);
            });
        })
            ->where('user_id', '<>', $user->id)
            ->orderBy('created_at','desc')
            ->limit(5)
            ->get();

		SEOTools::setTitle(trans('dashboard.page_name'));
		return view('dashboard',compact(
		    'order_counts',
            'success_procent',
            'success_total',
            'success_weight',
            'orders',
            'last_orders',
            'last_payment',
            'last_messages'
        ));
    }
}
