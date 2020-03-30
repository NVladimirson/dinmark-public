<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class NotificationController extends Controller
{
    public function index(){
		SEOTools::setTitle(trans('notification.page_name'));
		$notifications = auth()->user()->notifications()->paginate(10);
		$notifications->markAsRead();

    	return view('notification.index', compact('notifications'));
	}

	public function markRead(Request $request){
		auth()->user()->unreadNotifications()->where('created_at','<=',$request->last_notification)->limit(5)->update(['read_at' => now()]);

    	return 'ok';
	}
}
