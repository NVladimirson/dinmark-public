<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket\TicketMessage;
use App\Notifications\NewMessage;
use App\User;
use Illuminate\Http\Request;

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
}
