<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $password = strip_tags($request->input('password'));
        $email = strip_tags($request->input('email'));

        $user = User::where('email', $email)->first();
		$error = [];
		if(isset($user))
		{
			$md5_password = $this->getPassword($user->id,$user->email,$password, false);

			if ($md5_password == $user->password)
			{
				Auth::login($user);
				return redirect('/');

			}else{
				$error['password'] = trans('auth.password');
			}

		}else{
			$error['email'] =  trans('auth.email');
		}

        return redirect()->back()->withErrors($error);
    }

	protected function getPassword($id, $email, $password, $sequred = false)
	{
		if(!$sequred) $password = md5($password);
		return sha1($email . $password . SYS_PASSWORD . $id);
	}

}
