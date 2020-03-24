<?php

namespace App\Http\Controllers;

use App\Models\User\UserDataChangeRequest;
use App\Models\User\UserInfo;
use App\Services\User\PasswordCrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Artesaos\SEOTools\Facades\SEOTools;

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
			$path = Storage::disk('main_site')->putFile('images/profile', $request->file('photo'));
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
		}elseif($userPhone->value != ''){
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
				UserDataChangeRequest::updateOrCreate([
					'type' => 'email',
					'user_id' => $user->id
				],[
					'value' => mb_strtolower($request->email),
					]);
			}

			if(array_key_exists  ('phone',$validateRule)){
				UserDataChangeRequest::updateOrCreate([
					'type' => 'phone',
					'user_id' => $user->id
				],[
					'value' => mb_strtolower($request->phone),
				]);
			}

		}else{
			return redirect()->back();
		}

		return redirect()->back()->with('status', trans('user.edit_request_success'));
	}
}
