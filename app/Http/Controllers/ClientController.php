<?php

namespace App\Http\Controllers;

use App\Models\Company\Client;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class ClientController extends Controller
{
	function index(){
		SEOTools::setTitle(trans('client.page_list'));
		return view('client.index');
	}

	public function ajax(Request $request){
		$clients = Client::with(['company'])
			->whereHas('company',function ($companies){
				$companies->where([
					['holding', auth()->user()->getCompany->holding],
					['holding', '<>', 0],
				])->orWhere([
					['id', auth()->user()->getCompany->id],
				]);
			});

		return datatables()
			->eloquent($clients)
			->addColumn('actions', function (Client $client) {
				return view('client.include.action_buttons',compact('client'));
			})
			->rawColumns(['actions'])
			->toJson();
	}

	public function destroy($id){
		Client::destroy($id);

		return 'ok';
	}
}
