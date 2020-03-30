<?php

namespace App\Http\Controllers;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Notifications\NewMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class ChatController extends Controller
{
    public function index(){
		SEOTools::setTitle(trans('chat.page_name'));

		$chats = Chat::where('user_id',auth()->user()->id)
			->orWhere('manager_id',auth()->user()->id)
			->withCount(['messages' => function($q){
				$q->where([
					['is_new',1],
					['user_id','<>',auth()->user()->id],
				]);
			}])
			->orderBy('updated_at','desc')
			->paginate(10);

    	return view('chat.index',compact('chats'));
	}

	public function create(){
		SEOTools::setTitle(trans('chat.create_page_name'));
		return view('chat.create');
	}

	public function store(Request $request){
		$validatedData = $request->validate([
			'subject'		=> 'required|max:255',
			'message'		=> 'required',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return redirect()->back()->withErrors($validatedData);
			}
		}

		$toUser = auth()->user();
		$user = auth()->user()->id;
		$manager = $user;
		$isManage = (auth()->user()->type == 1 || auth()->user()->type == 2);

		if($isManage){
			$user = $request->id;
		}else{
			$manager = auth()->user()->getCompany->getManager->id;
			$toUser = auth()->user()->getCompany->getManager;
		}

		$chat = Chat::create([
			'subject' => $request->subject,
			'user_id' => $user,
			'manager_id' => $manager
		]);

		$message = ChatMessage::create([
			'text' => $request->message,
			'chat_id' => $chat->id,
			'user_id' => auth()->user()->id
		]);

		$toUser->notify(new NewMessage($message));

		return redirect()->route('chat.show',[$chat->id]);
	}

	public function show($id){
		$chat = Chat::find($id);

		SEOTools::setTitle(trans('chat.dialog').': '.$chat->subject);

		ChatMessage::where('is_new', 1)
			->where('user_id', '<>', auth()->user()->id)
			->update(['is_new' => 0]);

		return view('chat.show',compact('chat'));
	}


	public function update($id, Request $request){
		$chat = Chat::find($id);
		$chat->updated_at = Carbon::now();
		$chat->save();

		$toUser = $chat->user;
		if($chat->user_id == auth()->user()->id){
			$toUser = $chat->manager;
		}

		$message = ChatMessage::create([
			'text' => $request->text,
			'chat_id' => $chat->id,
			'user_id' => auth()->user()->id
		]);

		$toUser->notify(new NewMessage($message));

		return redirect()->route('chat.show',[$chat->id]);
	}
}
