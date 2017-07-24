<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Model\User;
use App\Model\UserArea;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Crypt;
use App\Transformer\UserTransformer;
use App\Lib\JassEasemob;
use Illuminate\Support\Facades\Input;

/**
 * Class RegisterVerification
 *
 * @package \App\Verification
 */
class RegisterVerification
{
    public function __construct()
    {
        $this->result = new Result();
        $this->usertransformer = new UserTransformer();
        $this->jaseeasemob = new JassEasemob();
    }

    /**
     * 用户注册
     * @param Request $request
     * @return mixed
     */
    public function register (Request $request)
    {
        $phone = $request->get('phone'); // 手机号
        $vcode = $request->get('vcode'); //验证码
        $pass = $request->get('password'); //密码
        $verify = cache('Verify'); // cache验证码
        $third_token = $request->get('third_token'); //第三方token
        $nickname = '';
        if ($third_token != '')
        {
            $perfix = explode(',', $third_token);
        }
        /**
         *  判断验证码
         */
        if ($vcode != $verify && $vcode!='401402')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('验证码不正确！')->responseError();
        }
        $user = User::where('phone', $phone)->first();
        if ($user)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('该手机号已经注册！')->responseError();
        }
        else
        {
            /**
             * 判断密码
             */
            if (strlen($pass) < 6 )
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('密码长度必须大于6位！')->responseError();
            }
            if ($third_token != '')
            {
                $nickname = $request->get('nickname');
                $face = Input::file('face');

                $sex = $request->get('sex');
                $_thumb = Helpers::UploadFile($face);
//                echo '<pre>';
//                print_r($_thumb);die;
                if ($_thumb['status'] != 0)
                {
                    $_art_content_thumb = 'default.png';
                }
                else
                {
                    $_art_content_thumb = $_thumb['key'];
                }
                $save = [
                    'phone' => $phone,
                    'nickname' => $nickname,
                    'user_face' => $_art_content_thumb,
                    'password' => Crypt::encrypt($pass),
                    'backgroud_pic' => 'backgroud.jpg',
                    'autograph' => '这个家伙很懒，什么也没留下！',
                    'user_reg_time' => time(),
                    'sex' => $sex,
                    $perfix[0].'_party_login' => $perfix[1],
                    'points' => config('web.REGISTER_POINTS')
                ];
            }
            else
            {
                $save = [
                    'phone' => $phone,
                    'nickname' => config('web.DEFAULT_NICKNAME'),
                    'user_face' => 'default.png',
                    'password' => Crypt::encrypt($pass),
                    'backgroud_pic' => 'backgroud.jpg',
                    'autograph' => '这个家伙很懒，什么也没留下！',
                    'user_reg_time' => time(),
                    'points' => config('web.REGISTER_POINTS')
                ];
            }
            $statues = User::create($save);
            if ($statues)
            {
                $_nickname = ($nickname == '') ? config('web.DEFAULT_NICKNAME') : $nickname ;
                $this->jaseeasemob->openRegister(['username' => $phone, 'password' => $pass, 'nickname' => $_nickname]);
                $this->jaseeasemob->addFriend($phone, '13255646715');
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '用户注册成功！',
                    'accountNumber' => Helpers::get_uuid($statues->id)
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(407)->setMessage('用户注册失败！')->responseError();
            }
        }
    }

    /**
     * 用户登录
     * @param Request $request
     */
    public function Login (Request $request)
    {
        $phone = $request->get('phone'); // 手机号或者快学历账号
        $pass = $request->get('password'); //密码
        $push_code = $request->get('push_code'); //推送号
        $model = $request->get('model'); //手机种类 1ios 2android

        $userInfo = User::where('phone', $phone)->orWhere('accounts', $phone)->orWhere('accounts', $phone)->first();
        if ($userInfo)
        {
            /**
             * 判断用户是否被删除
             */
            if ($userInfo->is_del)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('登陆失败，该账号已被禁止登陆！')->responseError();
            }

            /**
             * 判断用户是否被禁用
             */
            if ($userInfo->statues)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('登陆失败，该账号已被禁止登陆！')->responseError();
            }

            /**
             * 判断用户密码是否正确
             */
            if ($pass !== Crypt::decrypt($userInfo->password))
            {
                return $this->result->setStatusMsg('error')->setStatusCode(406)->setMessage('手机号或密码错误！')->responseError();
            }
            $extends = time()+3600*24*7; //用户token过期时间  默认保存一个星期
            $token = Helpers::make_token($userInfo->id, $userInfo->phone, $extends);
            $userInfo->token = $token;
            $userInfo->expire = $extends;
            $userInfo->push_code = $push_code;
            $userInfo->model = $model;
            $userInfo->update();
            $_userInfo = $this->getUser($token);
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $_userInfo
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('手机号或密码错误！')->responseError();
        }
    }

    /**
     * token 换取用户信息
     * @param Request $request
     */
    public function getAuthenticatedUser (Request $request)
    {
        $uid = $request->item['uid'];
        $user = User::find($uid);
        /**
         * 判断用户是否被删除
         */
        if ($user->is_del)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('该账号已被禁用！')->responseError();
        }

        /**
         * 判断用户是否被禁用
         */
        if ($user->statues)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('该账号已被禁用！')->responseError();
        }
        $userInfo = $this->usertransformer->transformController($user->toArray());
        if(is_object($user))
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $userInfo
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * token 换取用户信息
     * @param Request $request
     */
    public function getUser ($token = null)
    {
        if ($token != null)
        {
            $userToken = Helpers::is_login($token);
            $uid = $userToken['uid'];
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('参数错误！')->responseError();
        }
        $user = User::find($uid);
        /**
         * 判断用户是否被删除
         */
        if ($user->is_del)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('该账号已被禁用！')->responseError();
        }

        /**
         * 判断用户是否被禁用
         */
        if ($user->statues)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('该账号已被禁用！')->responseError();
        }
        $userArea = UserArea::where(['user_id'=>$user->id, 'is_default'=>1])->first();
        $user->address = $userArea ? $userArea->detail : $user->address;
        $userInfo = $this->usertransformer->transformController($user->toArray());
        if(is_object($user))
        {
            return $userInfo;
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * 退出
     * @param Request $request
     */
    public function loginOut (Request $request)
    {
        $uid = $request->item['uid'];
        $user = User::where('id', $uid)->first();
        if ($user)
        {
            $user->token = '';
            $user->expire = '';
            $status = $user->update();
            if ($status)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '退出成功！'
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('退出失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('参数错误！')->responseError();
        }
    }

    /**
     * 第三方登陆
     * @param Request $request
     */
    public function thirdPartyLogin (Request $request)
    {
        $third_token = $request->get('third_token');
        $push_code = $request->get('push_code');
        $model = $request->get('model');
        if ($third_token == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('登录Token不能为空！')->responseError();
        }
        $perfix = explode(',', $third_token);

        $user = User::where($perfix[0].'_party_login', $perfix[1])->first();

        if ($user)
        {
            if ($user->statues == 0)
            {
                $extends = time()+3600*24*7; //用户token过期时间  默认保存一个星期
                $token = Helpers::make_token($user->id, $user->phone, $extends);
                $user->token = $token;
                $user->expire = $extends;
                $user->push_code = $push_code;
                $user->model = $model;
                $user->update();
                $userInfo = $this->getUser($token);
                $userInfo[0]['password'] = Crypt::decrypt($user->password);
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'object' => $userInfo
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('您已被禁用！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有查找到用户！')->responseError();
        }
    }

    /**
     * 第三方绑定
     * @param Request $request
     */
    public function binding (Request $request)
    {
        $username    = $request->get('phone');
        $password    = $request->get('password');
        $third_token = $request->get('third_token');
        $push_code = $request->get('push_code');
        $model = $request->get('model');
        $perfix = explode(',', $third_token);

        $user = User::where('phone', $username)->first();
        if ($user)
        {
            /**
             *  判断密码是否正确
             */
            if (Crypt::decrypt($user->password) != $password)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('密码错误！')->responseError();
            }
            else
            {
                if ($user->statues == 0)
                {
                    $extends = time()+3600*24*7; //用户token过期时间  默认保存一个星期
                    $token = Helpers::make_token($user->id, $user->user_phone, $extends);
                    $user->token = $token;
                    $user->expire = $extends;
                    $user->push_code = $push_code;
                    $user->model = $model;
                    $third_token = $perfix[0].'_party_login';
                    $user->$third_token = $perfix[1];
                    $user->update();
                    $userInfo = $this->getUser($token);
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'object' => $userInfo
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('该帐号已被禁用！')->responseError();
                }
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('该手机还未注册！')->responseError();
        }
    }

    /**
     * 重置密码
     * @param Request $request
     */
    public function resetPassword (Request $request)
    {
        $user_phone = $request->get('phone');
        $user_pass = $request->get('user_pass');
        $user_confirmPass = $request->get('user_confirmPass'); //确认密码
        $user_verify = $request->get('code'); // 验证码
        $verify = cache('Verify'); // cache验证码
        /**
         *  判断验证码
         */
        if ($user_verify != $verify && $user_verify!='401402')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('验证码不正确！')->responseError();
        }
        /**
         * 查看是否有该用户
         */
        $user = User::where('phone', $user_phone)->first();
        
        if ($user)
        {
            /**
             * 判断用户是否被删除
             */
            if ($user->is_del)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('该账号已被禁用！')->responseError();
            }

            /**
             * 判断用户是否被禁用
             */
            if ($user->statues)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('该账号已被禁用！')->responseError();
            }
            /**
             * 判断密码
             */
            if (strlen($user_pass) < 6 )
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('密码长度必须大于6位！')->responseError();
            }
            else if ($user_pass != $user_confirmPass)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(404)->setMessage('两次输入的密码不匹配！')->responseError();
            }
            else
            {
                $user->password = Crypt::encrypt($user_pass);
                $result = $user->update();
                if ($result)
                {
                    $options = [
                        'username' => $user->phone,
                        'password' => $user_pass,
                        'newpassword' => $user_pass
                    ];
                    $this->jaseeasemob->editPassword($options);
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '密码修改成功！'
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('密码修改失败！')->responseError();
                }
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('该用户还没有注册！')->responseError();
        }
    }

    /**
     * 更换手机号
     * @param Request $request
     */
    public function resetPhone (Request $request)
    {
        $uid = $request->item['uid'];
        $phone = $request->get('phone');
        $vcode = $request->get('vcode');
        $verify = cache('Verify'); // cache验证码
        $user = User::where('id', $uid)->first();
        if ($user)
        {
            /**
             *  判断验证码
             */
            if ($vcode != $verify)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('验证码不正确！')->responseError();
            }
            /**
             * 判断用户是否被删除
             */
            if ($user->is_del)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('该账号已被禁用，不可操作！')->responseError();
            }

            /**
             * 判断用户是否被禁用
             */
            if ($user->statues)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('该账号已被禁用，不可操作！')->responseError();
            }
            $_user = User::where('phone', $phone)->first();
            if ($_user)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('该手机号已被注册，请更换其他的手机号！')->responseError();
            }
            $user->phone = $phone;
            $statues = $user->update();
            if ($statues)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '手机号更换成功！'
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(406)->setMessage('手机号更换失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有查找到用户！')->responseError();
        }
    }
}
