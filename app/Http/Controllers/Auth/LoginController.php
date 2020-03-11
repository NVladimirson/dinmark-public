<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Crypt;

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
    protected $redirectTo = '/dashboard/v1';

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

        $string = md5($password);
        $encrypted = Crypt::encrypt($string);
        $decrypted_string = Crypt::decrypt($encrypted);

        if(!empty($user) && $decrypted_string == $user->password){

            return view('pages/dashboard-v1');

        }

        return view('pages/login-v2');
    }

    public function logout()
    {
        return view('pages/login-v2');
    }

}
