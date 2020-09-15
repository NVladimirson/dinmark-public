<?php

namespace App\Http\Middleware;

use App\Models\Company\Company;
use App\Services\Order\ImplementationServices;
use App\Services\Order\OrderServices;
use App\Services\Product\CatalogServices;
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

            $wishlists_count = CatalogServices::getByCompany()->count();
            $implementation_count = ImplementationServices::getByCompany()->count();
            $orders_count = OrderServices::getByCompany()->count();


			view()->share(compact('companies','curent_company','logo','wishlists_count','implementation_count','orders_count'));
		}


        return $next($request);
    }
}
