<?php

namespace App\Http\Controllers;

use App\Models\Company\CompanyDocument;
use App\Models\Company\CompanyPrice;
use App\Models\Log\Log;
use App\Models\Log\LogAction;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketMessage;
use App\Notifications\NewMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Image;
use Artesaos\SEOTools\Facades\SEOTools;
use Ramsey\Uuid\Uuid;

class CompanyController extends Controller
{
    public function index(){
    	$company = auth()->user()->getCompany;

		SEOTools::setTitle(trans('company.edit_page_name'));
		return view('company.profile',compact('company'));
	}

	public function updateData(Request $request){
		$validatedData = $request->validate([
			'name'		=> 'required|max:255',
			'prefix'	=> 'nullable|max:255',
			'logo'		=> 'nullable|image',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}

		$company = auth()->user()->getCompany;
		$company->name = $validatedData['name'];
		$company->prefix = $validatedData['prefix'];
		if($request->hasFile('logo')){
			$path   = $request->file('logo');
			$full = Image::make($path)->encode('jpg');
			$resize = Image::make($path)->fit(300)->encode('jpg');
			$hash = md5($resize->__toString());
			$path = "images/company/{$company->id}_{$hash}.jpg";
			$full_path = "images/company/{$company->id}_{$hash}_full.jpg";
			$resize->save(Storage::disk('main_site')->getAdapter()->getPathPrefix() .$path);
			$full->save(Storage::disk('main_site')->getAdapter()->getPathPrefix() .$full_path);
			$path = basename($path);
			$full_path = basename($full_path);
			$company->logo = $path;
			$company->full_logo = $full_path;
		}

		$company->save();

		return redirect()->back()->with('status', trans('company.edit_personal_data_success'));
	}

	public function addPrice(Request $request)
	{
		$validatedData = $request->validate([
			'price_name'		=> 'required|max:255',
			'price_koef'	=> 'required|numeric|min:1',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}

		CompanyPrice::create([
			'name' => $validatedData['price_name'],
			'koef' => $validatedData['price_koef'],
			'company_id' => auth()->user()->getCompany->id,
		]);

		return redirect()->back()->with('status', trans('company.price_add_success'));
	}

	public function destroyPrice($id)
	{
		CompanyPrice::destroy($id);

		return redirect()->back()->with('status', trans('company.price_destroy_success'));
	}

	public function addDocument(Request $request)
	{
		$validatedData = $request->validate([
			'document_name'		=> 'required',
			'document_info'		=> 'required',
			'document'			=> 'required|file',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}
		$document = '';
		if($request->hasFile('document')){
			$document = Uuid::uuid4().'.'.$request->file('document')->getClientOriginalExtension();
			Storage::disk('main_site')->putFileAs('documents/'.auth()->user()->getCompany->id.'/'.$request->document_type, $request->file('document'), $document);
		}

		CompanyDocument::create([
			'name' => $validatedData['document_name'],
			'info' => $validatedData['document_info'],
			'folder' => $request->document_type,
			'document' => $document,
			'manager_add' => auth()->user()->id,
			'manager_edit' => auth()->user()->id,
			'company_id' => auth()->user()->getCompany->id
		]);


		Log::create([
			'date' => Carbon::now()->timestamp,
			'do' => LogAction::where('name','b2b_add_document_user')->first()->id,
			'user' => auth()->user()->id,
			'additionally' => $request->ip()
		]);

		return redirect()->back()->with('status', trans('company.document_add_success'));
	}

	public function destroyDocument($id)
	{
		CompanyDocument::destroy($id);

		return redirect()->back()->with('status', trans('company.document_destroy_success'));
	}

	public function requestDocument(Request $request)
	{
		$user = auth()->user();
		$toUser = null;
		if($user->getCompany){
			if($user->getCompany->getManager){
				$toUser = $user->getCompany->getManager;
			}
		}

		$validatedData = $request->validate([
			'request_text'		=> 'required',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}

		DB::beginTransaction();
		$ticket = Ticket::create([
			'subject' => trans('company.document_request_subject'),
			'user_id' => $user->id,
			'manager_id' => $toUser->id
		]);

		$message = TicketMessage::create([
			'text' => $validatedData['request_text'],
			'ticket_id' => $ticket->id,
			'user_id' => $user->id
		]);

		DB::commit();
		$toUser->notify(new NewMessage($message));

		return redirect()->back()->with('status', trans('company.document_send_success'));
	}
}
