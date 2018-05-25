<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        $fieldName = 'mail_address';

        return $fieldName;
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

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $request->only('mail_address', 'password');

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            $response = [
                'status' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'yomi_name' => $user->yomi_name,
                    'mail_address' => $user->mail_address,
                    'company_name' => $user->company_name,
                    'birthday' => $user->birthday,
                    'authority' => $user->authority,
                    'memo' => $user->memo,
                ]
            ];
            return response()->json($response, 200);
        }else{
            $response = [
              'status' => 'failed'
            ];
            return response()->json($response, 500);
        }

    }

//    public function logout(Request $request)
//    {
//        $this->guard()->logout();
//
//        $logout = $request->session()->invalidate();
//        if($logout){
//            $response = [
//                'status' => 'Logout successful'
//            ];
//            $status = 200;
//        }else{
//            $response = [
//                'status' => 'Logout failed'
//            ];
//            $status = 500;
//        }
//        return response()->json($response, $status);
//    }
}
