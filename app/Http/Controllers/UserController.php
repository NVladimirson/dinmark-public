<?php

namespace App\Http\Controllers;

use App\Models\Log\Log;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketMessage;
use App\Models\User\UserDataChangeRequest;
use App\Models\User\UserInfo;
use App\Notifications\NewMessage;
use App\Notifications\UserChangeData;
use App\Services\User\PasswordCrypt;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    function profile(){
		SEOTools::setTitle(trans('user.edit_page_name'));
    	return view('user.profile');
	}

	function updateData(Request $request){
		$validatedData = $request->validate([
			'name'		=> 'required|max:255',
			'birthday'	=> 'nullable|date',
			'photo'		=> 'nullable|image',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}

		$user = auth()->user();
		$user->name = $validatedData['name'];
		if($request->hasFile('photo')){
			$path   = $request->file('photo');
			$resize = Image::make($path)->fit(300)->encode('jpg');
			$hash = md5($resize->__toString());
			$path = "/images/profile/{$hash}.jpg";
			$resize->save(Storage::disk('main_site')->getAdapter()->getPathPrefix() .$path);
			$path = basename($path);
			$user->photo = $path;
		}

		$user->save();

		$userBirthday = UserInfo::where([
			['user',$user->id],
			['field','birthday'],
		])->first();

		if($userBirthday){
			$userBirthday->value = $validatedData['birthday'];
			$userBirthday->date = Carbon::now()->timestamp;
			$userBirthday->save();
		}else{
			if($validatedData['birthday'] != ''){
				UserInfo::create([
					'user' => $user->id,
					'field' => 'birthday',
					'value' => $validatedData['birthday'],
					'date' => Carbon::now()->timestamp
				]);
			}
		}

		return redirect()->back()->with('status', trans('user.edit_personal_data_success'));
	}

	public function updatePassword(Request $request){
		$validatedData = $request->validate([
			'password'		=> 'required|min:8|required_with:password_confirmation|confirmed',
		]);

		if(!is_array($validatedData) ){
			if($validatedData->fails()) {
				return Redirect::back()->withErrors($validatedData);
			}
		}

		$user = auth()->user();
		$user->password = PasswordCrypt::getPassword($user->id,$user->email,$validatedData['password'], false);
		$user->save();

		return redirect()->back()->with('status', trans('user.edit_password_success'));
	}

	public function chageRequest(Request $request){
		$user = auth()->user();
		$toUser = null;
		if($user->getCompany){
			if($user->getCompany->getManager){
				$toUser = $user->getCompany->getManager;
			}
		}

		$userPhone = UserInfo::where([
			['user',$user->id],
			['field','phone'],
		])->first();

		$validateRule = [];
		if ($user->email != $request->email && $user->email != ''){
			$validateRule['email'] = 'nullable|email|unique:wl_users';
		}

		if($userPhone)
		{
			if($userPhone->value != $request->phone && $userPhone->value != ''){
				$validateRule['phone'] = 'nullable|unique:wl_user_info,value';
			}
		}elseif($request->phone != ''){
			$validateRule['phone'] = 'nullable|unique:wl_user_info,value';
		}

		if(count($validateRule) > 0){
			$validatedData = $request->validate($validateRule);

			if(!is_array($validatedData) ){
				if($validatedData->fails()) {
					return Redirect::back()->withErrors($validatedData);
				}
			}
			if(array_key_exists  ('email',$validateRule)){
				DB::beginTransaction();
				$ticket = Ticket::create([
					'subject' => trans('user.request_subject'),
					'user_id' => $user->id,
					'manager_id' => $toUser->id
				]);

				$message = TicketMessage::create([
					'text' => trans('user.request_email_message',['old' => $user->email, 'new' => mb_strtolower($request->email)]),
					'ticket_id' => $ticket->id,
					'user_id' => $user->id
				]);

				DB::commit();
				$toUser->notify(new NewMessage($message));

			}

			if(array_key_exists  ('phone',$validateRule)){
				DB::beginTransaction();
				$ticket = Ticket::create([
					'subject' => trans('user.request_subject'),
					'user_id' => $user->id,
					'manager_id' => $toUser->id
				]);

				$messageText = trans('user.request_set_phone_message',['new' => mb_strtolower($request->phone)]);

				if($userPhone){
					$messageText = trans('user.request_phone_message',['old' => $userPhone->value, 'new' => mb_strtolower($request->phone)]);
				}

				$message = TicketMessage::create([
					'text' => $messageText,
					'ticket_id' => $ticket->id,
					'user_id' => $user->id
				]);
				DB::commit();
				$toUser->notify(new NewMessage($message));
			}

		}else{
			return redirect()->back();
		}

		return redirect()->back()->with('status', trans('user.edit_request_success'));
	}

	public function changeCompany($id){
		session(['current_company_id' => $id]);
		return redirect()->back();
	}

	public function log(){
		$logs = Log::whereHas('action', function ($q){
			$q->where('public',1);
		})
			->where([
				['user',auth()->user()->id],
				['do','<>',7],
				['do','<>',15],
			])
			->orderBy('date','desc')
			->paginate(20);
		SEOTools::setTitle(trans('user.log_page_name'));
		return view('user.log',compact('logs'));
	}

	public function loginToSite()
    {
        $user = auth()->user();
        $key = md5('b2b login/as_user'.time());
        $user->key_b2b = $key;
        $user->save();

        //return redirect(env('DINMARK_URL').'login/as_b2b_user?key='.$key,303);
        return redirect('https://dinmark.com.ua/login/as_b2b_user?key='.$key,303);
    }
}
