<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\UserDataChangeRequest;
use App\Models\User\UserInfo;
use App\Notifications\UserChangeDataAnswer;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class UserController extends Controller
{
    public function chageRequest($id){
		SEOTools::setTitle(trans('admin_user.change_data_page_name'));

		$changeData = UserDataChangeRequest::find($id);

		$userPhone = UserInfo::where([
			['user',$changeData->user_id],
			['field','phone'],
		])->first();

		if($userPhone){
			$userPhone = $userPhone->value;
		}

		return view('admin.user.change_data', compact('changeData','userPhone'));
	}

	public function chageRequestAnswer(Request $request, $id){
		$changeData = UserDataChangeRequest::find($id);
		$changeData->status = $request->submit;
		$changeData->save();

		$changeData->user->notify( new UserChangeDataAnswer($changeData));

		return redirect()->back();
	}
}
