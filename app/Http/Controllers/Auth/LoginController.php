<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Customize the default login authentication in laravel 6.*
     *
     * @return failed | success
     */
    function checklogin(Request $request){

        $this->validate($request, $this->validationRulesForLogin());

        $password = hash('tiger192,3',$request->get('password'));
        
        $user_data = array(
            'ADMIN_USERNAME'  => $request->get('username'),
            'ADMIN_PASSWORD' => $password,
            'LOGIN_ENABLED' => 1
        );

        if (User::where('ADMIN_USERNAME',$request->get('username'))->where('ADMIN_PASSWORD',$password)->first()){
            if(Auth::attempt($user_data)){
                return redirect('/');
            }else{
                return redirect('login')->withErrors([trans('auth.disabled')]);
            }
        }else{
            return redirect('login')->withErrors([trans('auth.failed')]);
        }
        
    }

    /**
     * Login Validation
     *
     * @return validation
     */
    public function validationRulesForLogin(){
        return [
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
