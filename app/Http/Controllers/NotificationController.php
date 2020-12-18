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
    $data = auth()->user()->unreadNotifications()->limit(5)->get();
    $tickets_to_search = [];
    foreach ($data as $key => $value) {
      $link = $value->data['link'];
      $ticket_id = preg_split("#/#", $link)[array_key_last(preg_split("#/#", $link))];
      $tickets_to_search[] = $ticket_id;
    }
		auth()->user()->unreadNotifications()->where('created_at','<=',$request->last_notification)->limit(5)->update(['read_at' => now()]);
    foreach ($tickets_to_search as $key => $ticket_id) {
      // code...
    }
    \DB::table('b2b_tickets')
              ->where('id',$ticket_id)
              ->update(
                ['new_for_user' => 0],
                ['new_for_manager' => 1]
              );
    	return 'ok';
	}
}
