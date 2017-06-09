<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\Cate;
use App\Model\Group;
use App\Model\Sell;
use App\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function index ()
    {
        $user_count = User::where('is_del', 0)->count();
        $sell_count = Sell::where('is_circle', 0)->count();
        $cate_sell = Cate::count();
        $group_count = Group::count();
        return view('admin.index.welcome', compact('user_count', 'sell_count', 'cate_sell', 'group_count'));
    }

    /**
     * 管理员修改密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pass ()
    {
        $user = session('user');
        if ($input = Input::all())
        {
            $rules = [
                'password' => 'required|between:6,20|confirmed',
            ];
            $message = [
                'password.required' => '新密码不能为空！',
                'password.between' => '新密码长度不够！',
                'password.confirmed' => '两次密码不一致！'
            ];
            $validator = Validator::make($input, $rules, $message);
            if ($validator->passes())
            {
                $users = Admin::where('admin_id', $user->admin_id)->first();
                $_password =  Crypt::decrypt($users->password);

                if ($input['password_o'] == $_password)
                {
                    $users->password = Crypt::encrypt($input['password']);
                    $users->update();
                    return back()->with('errors', '密码修改成功！');
                }
                else
                {
                    return back()->with('errors', '原密码错误！');
                }
            }
            else
            {
                return back()->withErrors($validator);
            }
        }
        else
        {
            return view('admin.index.pass', compact('user'));
        }
    }
}
