<?php

namespace App\Http\Middleware;

use App\Models\User\Company;
use Closure;

class UserCurrentCompany
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
    	if(auth()->user()->getCompany){
			if(!$request->session()->has('current_company_id')){
				session(['current_company_id' => auth()->user()->getCompany->id]);
			}
			$company = Company::find(session('current_company_id'));
			session(['current_company_name' => $company->name]);

			$companies = Company::where([
				['holding',$company->holding],
				['holding','<>',0]
			])->orWhere('id',$company->id)->get();

			view()->share(compact('companies'));
		}


        return $next($request);
    }
}
