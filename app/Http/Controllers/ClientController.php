<?php

namespace App\Http\Controllers;

use App\Models\Company\Client;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Redirect;

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

	public function create()
	{
		SEOTools::setTitle(trans('client.page_create'));
		return view('client.create');
	}

	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'name'		=> 'required',
			'phone'		=> 'required',
			'email'		=> 'required|email',
			'address'		=> 'required',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}

		$company = auth()->user()->getCompany;
		if(Client::whereHas('company',function ($companies){
				$companies->where([
					['holding', auth()->user()->getCompany->holding],
					['holding', '<>', 0],
				])->orWhere([
					['id', auth()->user()->getCompany->id],
				]);
			})
			->where(function ($client) use ($validatedData){
				$client->where('phone',$validatedData['phone'])
					->orWhere('email',$validatedData['email']);
			})->first()
		){
			return Redirect::back()->withError( trans('client.client_already_exist') )->withInput();
		}

		Client::create([
			'name' => $request->name,
			'company_name'  => $request->company,
			'company_edrpo'  => $request->edrpo,
			'email'  => $request->email,
			'phone'  => $request->phone,
			'address'  => $request->address,
			'company_id'  => $company->id,
		]);

		return redirect()->route('clients')->with('status', trans('client.create_success'));
	}

	public function edit($id)
	{
		$client = Client::find($id);
		SEOTools::setTitle($client->name);
		return view('client.edit',compact('client'));
	}

	public function update($id, Request $request)
	{
		$client = Client::find($id);
		$company = auth()->user()->getCompany;
		if(Client::whereHas('company',function ($companies){
			$companies->where([
				['holding', auth()->user()->getCompany->holding],
				['holding', '<>', 0],
			])->orWhere([
				['id', auth()->user()->getCompany->id],
			]);
		})
			->where(function ($client) use ($request){
				$client->where('phone',$request->phone)
					->orWhere('email',$request->email);
			})
			->where('id','<>',$client->id)
			->first()
		){
			return Redirect::back()->withError( trans('client.client_already_exist') )->withInput();
		}

		$client->name = $request->name;
		$client->company_name  = $request->company;
		$client->company_edrpo  = $request->edrpo;
		$client->email  = $request->email;
		$client->phone  = $request->phone;
		$client->address  = $request->address;
		$client->save();

		return redirect()->back()->with('status', trans('client.update_success'));

	}

	public function destroy($id){
		Client::destroy($id);

		return 'ok';
	}
}
