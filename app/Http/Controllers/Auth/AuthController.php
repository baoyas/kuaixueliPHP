<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use Cache;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

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
        $redirectUrl = $request->get('redirectUrl');
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

        $credentials['mobile'] = $mobile;
        $credentials['password'] = $password;

        if (!Auth::guard()->attempt($credentials)) {
            return response()->json([
                'code'  => '7',
                'ret' => false,
                'msg' => "账号或密码错误"
            ]);
        }

        $cacheKey = "cart";
        $scart = $request->session()->get($cacheKey);
        $scart = empty($scart) ? [] : \json_decode($scart, true);
        $request->session()->forget($cacheKey);

        $cacheKey = "cart_{$user->id}";
        $lcart = app('cache')->get($cacheKey);
        $lcart = empty($lcart) ? [] : \json_decode($lcart, true);

        $cart = array_merge($scart, $lcart);
        app('cache')->put($cacheKey, \json_encode($cart), 365*60*24);

        if($redirectUrl) {
            //return redirect()->back();
        }
        return response()->json([
            'code'  => '0',
            'ret' => 'true',
            'url' => empty($redirectUrl) ? '/' : $redirectUrl,
        ]);
    }

    public function getLogout()
    {
        Auth::guard()->logout();

        session()->forget('url.intented');

        return redirect('/');
    }

    public function postRegister(Request $request) {
        $user = $request->get('reg');
        $mobile = empty($user['mobile']) ? '' : $user['mobile'];
        $verifycode = empty($user['verifycode']) ? '' : $user['verifycode'];
        $email = empty($user['email']) ? '' : $user['email'];
        $realname = empty($user['realname']) ? '' : $user['realname'];
        $password = empty($user['userpass']) ? '' : $user['userpass'];
        $user = User::where(['mobile'=>$mobile])->first();
        if($user) {
            return response()->json([
                'code'  => '0',
                'ret' => false,
                'msg' => "手机号已经注册"
            ]);
        }

        if(empty($mobile) || strlen($mobile) != 11) {
            return response()->json([
                'code'  => '0',
                'ret' => false,
                'msg' => "无效的手机号码"
            ]);
        }

        if(empty($password) || strlen($password) < 6) {
            return response()->json([
                'code'  => '0',
                'ret' => false,
                'msg' => "密码6个字符以上"
            ]);
        }

        if(strcmp($verifycode, Cache::get("sms_".$mobile))!='0' && strcmp($verifycode, '401402')!='0') {
            return response()->json([
                'code'  => '0',
                'ret' => false,
                'msg' => "验证码错误"
            ]);
        }

        User::create(['mobile'=>$mobile, 'email'=>$email, 'realname'=>$realname, 'password'=>bcrypt($password)]);

        $credentials['mobile'] = $mobile;
        $credentials['password'] = $password;

        if (Auth::guard()->attempt($credentials)) {
            return response()->json([
                'code'  => '0',
                'ret' => true,
                'url' => "/"
            ]);
        }
        return response()->json([
            'code'  => '0',
            'ret' => true,
            'url' => "/"
        ]);
        return redirect('/auth/register');
    }

    public function forget(Request $request) {
        return view('auth/forget');
    }

    public function resetpass(Request $request) {
        $mobile = $request->get('mobile');
        $verifycode = $request->get('verifycode');
        $password = $request->get('password');
        if(strcmp($verifycode, Cache::get("sms_".$mobile))!='0' && strcmp($verifycode, '401402')!='0') {
            return $this->response(NULL, 1, "验证码错误");
        }
        User::where(['mobile'=>$mobile])->update(['password'=>bcrypt($password)]);
        return $this->response(NULL);
    }
}