<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers {
        AuthenticatesUsers::login as traitLogin;
    }

    protected $redirectPath = '/';

    public function getLogin() {
        return view('auth/login');
    }

    public function getRegister() {
        return view('auth/register');
    }

    public function postLogin(Request $request) {
        $user = $request->get('user');
        $mobile = empty($user['mobile']) ? '' : $user['mobile'];
        $password = empty($user['userpass']) ? '' : $user['userpass'];
        if(empty($mobile) || empty($password)) {
            return response()->json([
                'code'  => '7',
                'ret' => false,
                'msg' => "账号或密码错误"
            ]);
        }
        $user = User::where('mobile', $mobile)->first();
        if(empty($user)) {
            return response()->json([
                'code'  => '100',
                'ret' => false,
                'msg' => ""
            ]);
        }
        if ($password != Crypt::decrypt($user->password)) {
            return response()->json([
                'code'  => '7',
                'ret' => false,
                'msg' => "账号或密码错误"
            ]);
        }
        return response()->json([
            'code'  => '0',
            'ret' => 'true',
            'url' => "/"
        ]);
    }
}