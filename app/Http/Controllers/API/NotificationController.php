<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use App\Models\Order\Implementation;
use App\Models\Order\Order;
use App\Models\Queue;
use App\Models\Ticket\TicketMessage;
use App\Notifications\ImplementationNotification;
use App\Notifications\NewMessage;
use App\Notifications\OrderNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class NotificationController extends Controller
{
    public function ticket(Request $request){
		if($request->has('user_id')){
			$user = User::find($request->user_id);
			if(empty($user)){
				return response()->json([
					'status' => 'error',
					'message' => 'User not found'
				]);
			}
		}else{
			return response()->json([
				'status' => 'error',
				'message' => 'user_id required parameter'
			]);
		}

		if($request->has('message_id')){
			$message = TicketMessage::find($request->message_id);
			if(empty($message)){
				return response()->json([
					'status' => 'error',
					'message' => 'Message not found'
				]);
			}
		}else{
			return response()->json([
				'status' => 'error',
				'message' => 'message_id required parameter'
			]);
		}

		$user->notify(new NewMessage($message));

		return response()->json([
			'status' => 'success',
		]);
	}

    public function news(Request $request){

		if($request->has('news_id')){

			$news = News::find($request->news_id);
			if(empty($news)){
				return response()->json([
					'status' => 'error',
					'message' => 'News not found'
				]);
			}

			Queue::create([
                'name' => 'new_news',
                'entity_id' => $news->id,
                'step' => 50,
            ]);

		}else{
			return response()->json([
				'status' => 'error',
				'message' => 'news_id required parameter'
			]);
		}


		return response()->json([
			'status' => 'success',
		]);
	}

    public function order(Request $request){

        if($request->has('order_id')){
            $order = Order::find($request->order_id);
            if(empty($order)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found'
                ]);
            }
            $users = $order->getUser->getCompany->users;
            App::setLocale('ua');
            foreach ($users as $user){
                $user->notify(new OrderNotification($order));
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'order_id required parameter'
            ]);
        }
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function implementation(Request $request){

        if($request->has('implementation_id')){
            $implementation = Implementation::find($request->implementation_id);
            if(empty($implementation)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Implementation not found'
                ]);
            }
            $users = $implementation->customer->getCompany->users;
            App::setLocale('ua');
            foreach ($users as $user){
                $user->notify(new ImplementationNotification($implementation));
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'implementation_id required parameter'
            ]);
        }
        return response()->json([
            'status' => 'success',
        ]);
    }
}
