<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = 'company';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        $fieldName = 'mail_address';

        return $fieldName;
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }


    protected function validateLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                'mail_address' => 'required|email',
                'password' => 'required|string',
            ],
            [
                'mail_address.required' => 'Email is required !',
                'mail_address.email' => 'Email wrong format !',
                'password.required' => 'Password is required',
            ]
        );
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }
}
