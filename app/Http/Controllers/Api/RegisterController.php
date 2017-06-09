<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\RegisterVerification;

class RegisterController extends JaseController
{
    private $result;
    private $register;
    public function __construct ()
    {
        $this->result = new Result();
        $this->register = new RegisterVerification();
    }
    /**
     * 用户注册
     * @param Request $request
     */
    public function register (Request $request)
    {
        return $this->register->register($request);
    }

    /**
     * 发送短信验证码
     * @param Request $request
     */
    public function SendSms (Request $request)
    {
        include app_path() . '/Lib/Alisms/Notegory.php';
        $params = [
            'NOTE_USER' => Config::get('web.NOTE_USER'),
            'NOTE_PASS' => Config::get('web.NOTE_PASS')
        ];
        $note = new \Notegory($params);
        $statues = $note->noteGory($request->get('phone'));
        if ($statues == 0)
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '验证码发送成功！'
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('验证码发送失败！')->responseError();
        }
    }

    /**
     * 用户登录
     * @param Request $request
     */
    public function Login (Request $request)
    {
        return $this->register->Login($request);
    }

    /**
     * token换取用户信息
     * @param Request $request
     * @return mixed
     */
    public function getAuthenticatedUser (Request $request)
    {
        return $this->register->getAuthenticatedUser($request);
    }

    /**
     * 退出登录
     * @param Request $request
     */
    public function loginOut (Request $request)
    {
        return $this->register->loginOut($request);
    }

    /**
     * 第三方登陆
     * @param Request $request
     * @return mixed
     */
    public function thirdPartyLogin (Request $request)
    {
        return $this->register->thirdPartyLogin($request);
    }

    /**
     * 第三方绑定
     * @param Request $request
     */
    public function binding (Request $request)
    {
        return $this->register->binding($request);
    }

    /**
     * 重置密码
     * @param Request $request
     */
    public function resetPassword (Request $request)
    {
        return $this->register->resetPassword($request);
    }

    /**
     * 更换手机号
     * @param Request $request
     */
    public function resetPhone (Request $request)
    {
        return $this->register->resetPhone($request);
    }
}
