<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    public function login ()
    {
        if ($input = Input::all())
        {
            if ($input['username'] == '' || $input['password'] == '')
            {
                return back()->with('msg', '用户名或密码错误！');
            }
            $user = Admin::where('username', $input['username'])->first();
            if ($input['password'] != Crypt::decrypt($user->password))
            {
                return back()->with('msg', '用户名或密码错误！');
            }
            else
            {
                $laravel = Request();
                $ip = $laravel->ip();
                $time = Carbon::now();
                $save = [
                    'login_ip' => $ip,
                    'login_time' => $time
                ];
                Admin::where('admin_id', $user->admin_id)->increment('login_num');
                Admin::where('admin_id', $user->admin_id)->update($save);
                session(['user' => $user]);
                return redirect('admin/index');
            }
        }
        else
        {
            return view('admin.login.login');
        }
    }

    /**
     * 退出登录
     */
    public function quit ()
    {
        session(['user' => null]);
        return redirect('admin');
    }
}
