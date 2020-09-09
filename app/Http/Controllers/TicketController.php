<?php

namespace App\Http\Controllers;

use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketMessage;
use App\Notifications\NewMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class TicketController extends Controller
{
    public function index(){
		SEOTools::setTitle(trans('ticket.page_name'));

		$tickets = Ticket::where('user_id',auth()->user()->id)
			->orWhere('manager_id',auth()->user()->id)
			->withCount(['messages' => function($q){
				$q->where([
					['is_new',1],
					['user_id','<>',auth()->user()->id],
				]);
			}])
			->orderBy('updated_at','desc')
			->paginate(10);

    	return view('ticket.index',compact('tickets'));
	}

    public function ajax(Request $request){
        $tickets = Ticket::with(['messages'])
            ->where(function($q){
                $q->where('user_id',auth()->user()->id)
                    ->orWhere('manager_id',auth()->user()->id);
            })
            ->withCount(['messages as messages_count','messages as new_messages_count' => function($q){
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
            ->addColumn('manager_html',function (Ticket $ticket){
                return view('ticket.include.user',['user'=>$ticket->manager])->render();
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
                return Carbon::parse($ticket->created_at)->format('d.m.Y h:i');
            })
            ->orderColumn('created_at_html', function ($ticket, $order){
                $ticket
                    ->orderBy('status','ASC')
                    ->orderBy('created_at', $order);
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
            ->rawColumns(['subject_html','user_html','manager_html','created_at_html','message_count_html'])
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
		$isManage = (auth()->user()->type == 1 || auth()->user()->type == 2);

		if($isManage){
			$user = $request->id;
		}else{
			$manager = auth()->user()->getCompany->getManager->id;
			$toUser = auth()->user()->getCompany->getManager;
		}

		$ticket = Ticket::create([
			'subject' => $request->subject,
			'user_id' => $user,
			'manager_id' => $manager
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

		$message = TicketMessage::create([
			'text' => $request->text,
			'ticket_id' => $ticket->id,
			'user_id' => auth()->user()->id
		]);

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
}
