<?php

namespace App\Http\Middleware;

use App\Models\Company\Company;
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
			$curent_company = Company::find(session('current_company_id'));
			session(['current_company_name' => $curent_company->name]);

			$companies = Company::where([
				['holding',$curent_company->holding],
				['holding','<>',0]
			])->orWhere('id',$curent_company->id)->get();

			$logo = env('DINMARK_URL').'images/empty-avatar.png';
			if($curent_company->logo){
				$logo = env('DINMARK_URL').'images/company/'.$curent_company->logo;
			}

			view()->share(compact('companies'));
			view()->share(compact('logo'));
			view()->share(compact('curent_company'));
		}


        return $next($request);
    }
}
