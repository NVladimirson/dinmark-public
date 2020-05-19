<?php

namespace App\Http\Middleware;

use App\Models\Ticket\Ticket;
use Closure;

class TicketSidebar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$tickets = Ticket::where('user_id',auth()->user()->id)
			->orWhere('manager_id',auth()->user()->id)
			->withCount(['messages' => function($q){
				$q->where([
					['is_new',1],
					['user_id','<>',auth()->user()->id],
				]);
			}])->get();
    	$countMessage = $tickets->sum('messages_count');
		view()->share(compact('countMessage'));
        return $next($request);
    }
}
