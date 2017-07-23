<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\UserVerification;
use App\Model\User;
use App\Transformer\UsersTransformer;

class UserController extends JaseController
{
    private $result;
    private $userverification;
    public function __construct()
    {
        $this->result = new Result();
        $this->userverification = new UserVerification();
        $this->userstransformer = new UsersTransformer();
    }

    /**
     * 用户更新头像
     * @param Request $request
     */
    public function changeFace (Request $request)
    {
        return $this->userverification->changeFace($request);
    }

    /**
     * 修改性别
     * @param Request $request
     */
    public function changeSex (Request $request)
    {
        return $this->userverification->changeSex($request);
    }

    /**
     * 修改签名
     * @param Request $request
     */
    public function changeSign (Request $request)
    {
        return $this->userverification->changeSign($request);
    }

    /**
     * 用户修改区域
     * @param Request $request
     */
    public function changeArea (Request $request)
    {
        return $this->userverification->changeArea($request);
    }

    /**
     * 查看用户信息
     * @param Request $request
     * @return mixed
     */
    public function userInfo (Request $request)
    {
        return $this->userverification->userInfo($request);
    }

    /**
     * 查看用户信息 for 用户手机号
     * @param Request $request
     */
    public function userInfoForPhone (Request $request)
    {
        return $this->userverification->userInfoForPhone($request);
    }

    /**
     * 修改用户昵称
     * @param Request $request
     * @return mixed
     */
    public function changeNickname (Request $request)
    {
        return $this->userverification->changeNickname($request);
    }

    /**
     * 给好友设置备注
     * @param Request $request
     * @return mixed
     */
    public function SetNotes (Request $request)
    {
        return $this->userverification->SetNotes($request);
    }

    /*
     * 设置朋友圈背景
     */
    public function userSetBackground (Request $request)
    {
        return $this->userverification->userSetBackground($request);
    }

    public function check (Request $request) {
        $user_id = $request->item['uid'];
        $user = User::find($user_id);
        $data = ['nickname'=>1, 'user_face'=>1];
        if($user->nickname==config('web.DEFAULT_NICKNAME') || empty($user->nickname)) {
            $data['nickname'] = 2;
        }
        if($user->user_face=='default.png'|| empty($user->user_face)) {
            $data['user_face'] = 2;
        }
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $data
        ]);
    }

    public function setAlipayAccount (Request $request)
    {
        return $this->userverification->setAlipayAccount($request);
    }

    public function unSetAlipayAccount (Request $request)
    {
        return $this->userverification->unSetAlipayAccount($request);
    }
    
    public function inviteCode (Request $request)
    {
        return $this->userverification->inviteCode($request);
    }
    
}
