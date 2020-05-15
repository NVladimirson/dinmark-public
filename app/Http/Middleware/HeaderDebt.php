<?php

namespace App\Http\Middleware;

use App\Models\Order\ImplementationProduct;
use App\Models\Order\Payment;
use Closure;

class HeaderDebt
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
    	$debt = 0;
		$implementationProducts = ImplementationProduct::whereHas('implementation', function ($implementations){
				$implementations->whereHas('sender',function ($users){
					$users->whereHas('getCompany',function ($companies){
						$companies->where([
							['id', session('current_company_id')],
						]);
					});
				})
					->orWhereHas('customer',function ($users){
						$users->whereHas('getCompany',function ($companies){
							$companies->where([
								['id', session('current_company_id')],
							]);
						});
					});
			})
			->get();

		$payments = Payment::whereHas('order',function ($orders){
			$orders->whereHas('getUser',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', session('current_company_id')],
					]);
				});
			})->orWhereHas('sender',function ($users){
				$users->whereHas('getCompany',function ($companies){
					$companies->where([
						['id', session('current_company_id')],
					]);
				});
			});
		})->get();

		$debt = $implementationProducts->sum('total') - $payments->sum('payed');

		view()->share(compact('debt'));
        return $next($request);
    }
}
