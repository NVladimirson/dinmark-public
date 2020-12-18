<?php

namespace App\Http\Controllers;

use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketMessage;
use App\Notifications\NewMessage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use App\Services\Product\CategoryServices;
use App\Events\NewMessage as NewMessageEvent;

class TicketController extends Controller
{
    public function index(){
		SEOTools::setTitle(trans('ticket.page_name'));

        $usersId =  Ticket::where(function($q){
                $q->where('user_id',auth()->user()->id)
                    ->orWhere('manager_id',auth()->user()->id);
            })
            ->groupBy('user_id')
            ->pluck('user_id');

        $users = User::whereIn('id',$usersId)->get();

        $managersId =  Ticket::where(function($q){
                $q->where('user_id',auth()->user()->id)
                    ->orWhere('manager_id',auth()->user()->id);
            })
            ->groupBy('manager_id')
            ->pluck('manager_id');

        $managers = User::whereIn('id',$managersId)->get();

        $locale = CategoryServices::getLang();

    	return view('ticket.index',compact('users','managers','locale'));
	}

    public function ajax(Request $request){
        $tickets = Ticket::with(['messages'])
            ->where(function($q){
                $q->where('user_id',auth()->user()->id)
                    ->orWhere('manager_id',auth()->user()->id);
            });

        if($request->has('date_from')){
            $tickets->where('updated_at','>=',$request->date_from);
        }


        if($request->has('date_to')){
            $tickets->where('updated_at','<=',$request->date_to);
        }

        if($request->has('status')){
            $tickets->where('status',$request->status);
        }

        if($request->has('user_id')){
            $tickets->where('user_id',$request->user_id);
        }

        if($request->has('manager_id')){
            $tickets->where('manager_id',$request->manager_id);
        }

        if($request->has('is_new_message')){
            if($request->is_new_message == 'new'){
                $tickets->whereHas('messages', function ($messages){
                    $messages->where([
                        ['is_new',1],
                        ['user_id','<>',auth()->user()->id],
                    ]);
                });
            }else{
                $tickets->whereHas('messages', function ($messages){
                    $messages->where([
                        ['is_new',0],
                        ['user_id','<>',auth()->user()->id],
                    ])->orWhere('user_id',auth()->user()->id);
                });
            }
        }




        $tickets->withCount(['messages as messages_count','messages as new_messages_count' => function($q){
                $q->where([
                    ['is_new',1],
                    ['user_id','<>',auth()->user()->id],
                ]);
            }]);


        return datatables()
            ->eloquent($tickets)
            ->addColumn('subject_html',function (Ticket $ticket){
                return '<a href="'.route('ticket.show',[$ticket->id]).'">'.$ticket->subject.'</a>';
            })
            ->addColumn('user_html',function (Ticket $ticket){
                return view('ticket.include.user',['user'=>$ticket->user])->render();
            })
            ->addColumn('status_html',function (Ticket $ticket){
                return trans('ticket.'.$ticket->status);
            })
            ->addColumn('manager_html',function (Ticket $ticket){
                return view('ticket.include.user',['user'=>$ticket->manager])->render();
            })
            ->addColumn('message_count_html',function (Ticket $ticket){
                return $ticket->messages_count;
            })
            ->addColumn('new_messages_count_html',function (Ticket $ticket){
                return $ticket->new_messages_count;
            })
            ->addColumn('created_at_html',function (Ticket $ticket){
                return Carbon::parse($ticket->updated_at)->format('d.m.Y h:i');
            })
            ->addColumn('action_buttons',function (Ticket $ticket){
                return view('ticket.include.action_buttons',compact('ticket'))->render();
            })
            ->orderColumn('created_at_html', function ($ticket, $order){
                $ticket
                    ->orderBy('status','ASC')
                    ->orderBy('updated_at', $order);
            })
            ->filterColumn('subject_html', function($ticket, $keyword) {
                $ticket
                    ->whereHas('messages', function ($q) use ($keyword){
                        $q->where('text', 'like',["%{$keyword}%"]);
                    })
                    ->orWhere('subject', 'like',["%{$keyword}%"]);

            })
           ->filter(function ($ticket) use ($request) {
                if(request()->has('subject_html')){
                    $ticket->whereHas('messages', function ($q){
                        $q->where('text', 'like',"%" . request('subject_html') . "%");
                    })
                        ->orWhere()
                        ->whereHas('subject', 'like',"%" . request('subject_html') . "%");
                }
            }, true)
            ->rawColumns(['subject_html','user_html','manager_html','created_at_html','message_count_html','action_buttons'])
            ->toJson();
    }

	public function create(){
		SEOTools::setTitle(trans('ticket.create_page_name'));
		return view('ticket.create');
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

    $message['sendto'] = auth()->user()->getCompany->getManager->id;
    $message['name'] =  auth()->user()->name;
    $message['email'] =  auth()->user()->email;
    $success = event(new NewMessageEvent($message));

		$isManage = (auth()->user()->type == 1 || auth()->user()->type == 2);

		if($isManage && ($request->id != null)){
			$user = $request->id;
		}else{
			$manager = auth()->user()->getCompany->getManager->id;
			$toUser = auth()->user()->getCompany->getManager;
      $user = auth()->user()->id;
		}
    $manager = auth()->user()->getCompany->getManager->id;

		$ticket = Ticket::create([
			'subject' => $request->subject,
			'user_id' => $user,
			'manager_id' => $manager,
      'new_for_user' => '1',
      'new_for_manager' => '1',
		]);

		$message = TicketMessage::create([
			'text' => $request->message,
			'ticket_id' => $ticket->id,
			'user_id' => auth()->user()->id
		]);

		$toUser->notify(new NewMessage($message));

		return redirect()->route('ticket.show',[$ticket->id]);
	}

	public function show($id){
		$ticket = Ticket::find($id);

		SEOTools::setTitle(trans('ticket.dialog').': '.$ticket->subject);

		TicketMessage::where('is_new', 1)
			->where('user_id', '<>', auth()->user()->id)
			->update(['is_new' => 0]);

		return view('ticket.show',compact('ticket'));
	}


	public function update($id, Request $request){
		$ticket = Ticket::find($id);
		$ticket->updated_at = Carbon::now();
		$ticket->save();

		$toUser = $ticket->user;
		if($ticket->user_id == auth()->user()->id){
			$toUser = $ticket->manager;
		}

    $message['sendto'] = auth()->user()->getCompany->getManager->id;
    $message['name'] =  auth()->user()->name;
    $message['email'] =  auth()->user()->email;
    $success = event(new NewMessageEvent($message));

		$message = TicketMessage::create([
			'text' => $request->text,
			'ticket_id' => $ticket->id,
			'user_id' => auth()->user()->id,
      'new_for_user' => 0,
      'new_for_manager' => 1,
      'email' => 0,
		]);

    \DB::table('b2b_tickets')
              ->where('id', $ticket->id)
              ->update(
                ['new_for_user' => 1],
                ['new_for_manager' => 1]
              );

		$toUser->notify(new NewMessage($message));

		return redirect()->route('ticket.show',[$ticket->id]);
	}

    public function explanation(Request $request)
    {
        $ticket = Ticket::create([
            'subject' => $request->explanation_subject,
            'user_id' => auth()->user()->id,
            'manager_id' => auth()->user()->getCompany->getManager->id
        ]);

        $message = TicketMessage::create([
            'text' => $request->explanation_message,
            'ticket_id' => $ticket->id,
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'status' => 'success',
            ]);
	}

    public function changeStatus($id)
    {
        $ticket = Ticket::find($id);
        $ticket->status = $ticket->status == 'close'? 'open' : 'close';
        $ticket->save();

        return response()->json([
            'status' => 'success',
        ]);
	}
}
