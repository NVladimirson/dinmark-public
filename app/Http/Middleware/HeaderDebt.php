<?php

namespace App\Http\Middleware;

use App\Models\Company\Company;
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
    	$company = Company::find(session('current_company_id'));

		$debt = $company->balance;

		view()->share(compact('debt'));
        return $next($request);
    }
}
