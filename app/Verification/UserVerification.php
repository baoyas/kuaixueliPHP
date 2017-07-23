<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Model\User;
use App\Model\UserArea;
use App\Model\Userremark;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use App\Transformer\UsersTransformer;
use App\Verification\RegisterVerification;
use App\Lib\JassEasemob;

/**
 * Class UserVerification
 *
 * @package \App\Verification
 */
class UserVerification
{
    public function __construct()
    {
        $this->result = new Result();
        $this->userstransformer = new UsersTransformer();
        $this->registerverification = new RegisterVerification();
        $this->jasseasemob = new JassEasemob();
    }

    /**
     * 用户修改头像
     * @param Request $request
     * @return mixed
     */
    public function changeFace (Request $request)
    {
        $uid = $request->item['uid'];
//        $file = Input::file('user_face');
//        $thumb = Helpers::UploadFile($file);
//        $_thumb = $thumb['key'];
        $_thumb = $request->get('user_face');
        $user = User::where('id', $uid)->first();
        if ($user)
        {
            $user->user_face = $_thumb;
            $stuse = $user->update();
            if ($stuse)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '头像修改成功！',
                    'new_face' => Config::get('web.QINIU_URL').'/'.$_thumb,
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('头像修改失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * 设置朋友圈背景
     * @param Request $request
     */
    public function userSetBackground (Request $request)
    {
        $uid = $request->item['uid'];
        $Background = $request->get('background');
        $user = User::where('id', $uid)->first();
        if ($user)
        {
            $user->backgroud_pic = $Background;
            $stuse = $user->update();
            if ($stuse)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '更新成功！',
                    'new_face' => Config::get('web.QINIU_URL').'/'.$Background,
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('更新失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * 修改性别
     * @param Request $request
     */
    public function changeSex (Request $request)
    {
        $uid = $request->item['uid'];
        $info = User::where('id', $uid)->first();
        if ($info)
        {
            $sex = $request->get('sex');
            if ($sex == '')
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('用户性别不能为空！')->responseError();
            }
            else
            {
                $info->sex = $sex;
                $re = $info->update();
                if ($re)
                {
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '性别修改成功！',
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('性别修改失败！')->responseError();
                }
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * 修改签名
     * @param Request $request
     */
    public function changeSign (Request $request)
    {
        $uid = $request->item['uid'];
        $info = User::where('id', $uid)->first();
        if ($info)
        {
            $sign = $request->get('sign');
            if ($sign == '')
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('签名不能为空！')->responseError();
            }
            else
            {
                $info->autograph = $sign;
                $re = $info->update();
                if ($re)
                {
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '签名更新成功！',
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('签名更新失败！')->responseError();
                }
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**\
     * 修改区域
     * @param Request $request
     */
    public function changeArea (Request $request)
    {
        $uid = $request->item['uid'];
        $info = User::where('id', $uid)->first();
        if ($info)
        {
            $sign = $request->get('area');
            if ($sign == '')
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('区域不能为空！')->responseError();
            }
            else
            {
                $info->area = $sign;
                $re = $info->update();
                if ($re)
                {
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '区域更新成功！',
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('区域更新失败！')->responseError();
                }
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * 查看用户信息
     * @param Request $request
     */
    public function userInfo (Request $request)
    {
        $uid = $request->item['uid'];
        $ta_uid = $request->get('uid');
        if ($uid == $ta_uid)
        {
            return $this->registerverification->getAuthenticatedUser($request);
        }
        $user = User::where('id', $uid)->first();
        if ($user)
        {
            $userArea = UserArea::where(['user_id'=>$user->id, 'is_default'=>1])->first();
            $user->address = $userArea ? $userArea->detail : $user->address;
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $this->userstransformer->transformController($user->toArray())
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * 查看用户信息 for 用户手机号
     * @param Request $request
     */
    public function userInfoForPhone (Request $request)
    {
        $uid = $request->item['uid'];
        $ta_uid = $request->get('phone');
        if ($uid == $ta_uid)
        {
            return $this->registerverification->getAuthenticatedUser($request);
        }
        $user = User::where('phone', $ta_uid)->first();
        if ($user)
        {
            $obj = $this->userstransformer->transformController($user->toArray());
            $remark = Userremark::where('uid', $uid)->where('friends_phone', $obj[0]['phone'])->first();
            if ($remark)
            {
                $obj[0]['remark'] = $remark->remarks;
                $obj[0]['describes'] = $remark->describes;
                $obj[0]['remark_phone'] = $remark->remark_phone;
            }
            else
            {
                $obj[0]['remark'] = "";
                $obj[0]['describes'] = "";
                $obj[0]['remark_phone'] = "";
            }
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $obj
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * 用户修改昵称
     * @param Request $request
     */
    public function changeNickname (Request $request)
    {
        $uid = $request->item['uid'];
        $info = User::where('id', $uid)->first();
        if ($info)
        {
            $sign = mb_convert_encoding($request->get('nickname'), 'UTF-8');
            if ($sign == '')
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('昵称不能为空！')->responseError();
            }
            elseif (mb_strlen($sign)>10)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('昵称不能大于10个字符！')->responseError();
            }
            else
            {
                $info->nickname = $sign;
                $re = $info->update();
                if ($re)
                {
                    $option = [
                        'username' => $info->phone,
                        'nickname' => "".$sign.""
                    ];
                    $this->jasseasemob->editNickname($option);
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '昵称更新成功！',
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('昵称更新失败！')->responseError();
                }
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    /**
     * 给好友设置备注
     * @param Request $request
     */
    public function SetNotes (Request $request)
    {
        $uid = $request->item['uid'];  //我的id
        $am_phone = $request->item['username']; //我的手机号
        $friends_phone = $request->get('friends_phone'); //好友的手机号
        $remarks = $request->get('remarks'); //给好友设置的备注
        $describe = $request->get('describes'); //给好友设置的描述
        $remark_phone = $request->get('remark_phone'); //给好友设置的联系电话

        if ($am_phone == $friends_phone)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('不能给自己设置备注！')->responseError();
        }

        $user_remark = Userremark::where('uid', $uid)->where('friends_phone', $friends_phone)->first();
        if ($user_remark)
        {
            $user_remark->remarks = $remarks;
            $user_remark->describes = $describe;
            $user_remark->remark_phone = $remark_phone;
            $statues = $user_remark->update();
            if ($statues)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '添加成功！',
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('添加失败！')->responseError();
            }
        }
        else
        {
            $save = [
                'uid' => $uid,
                'friends_phone' => $friends_phone,
                'remarks' => $remarks,
                'describes' => $describe,
                'remark_phone' => $remark_phone
            ];
            $statues = Userremark::create($save);
            if ($statues)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '添加成功！',
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('添加失败！')->responseError();
            }
        }
    }

    public function setAlipayAccount (Request $request)
    {
        $alipay_account = $request->get('alipay_account');
        if(empty($alipay_account)) {
            return $this->result->setStatusMsg('error')->setStatusCode(603)->setMessage('支付宝账号不能为空')->responseError();
        }
        $user_id = $request->item['uid'];
        $user = User::find($user_id);
        if ($user)
        {
            $user->alipay_account = $alipay_account;
            $stuse = $user->update();
            if ($stuse)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '绑定支付宝账号成功！'
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('绑定支付宝账号成功！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    public function unSetAlipayAccount (Request $request)
    {
        $user_id = $request->item['uid'];
        $user = User::find($user_id);
        if ($user)
        {
            $user->alipay_account = '';
            $stuse = $user->update();
            if ($stuse)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '解绑支付宝账号成功！'
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('解绑支付宝账号失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }

    public function inviteCode (Request $request)
    {
        $user_id = $request->item['uid'];
        $accounts = $request->get('accounts');
        $user = User::where(['accounts'=>$accounts])->first();
        if(empty($user) || empty($accounts)) {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('邀请码不存在')->responseError();
        }
        $user = User::find($user_id);
        if ($user)
        {
            if($user->parent_ldlcode) {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('已经设置过邀请码了')->responseError();
            }
            $user->parent_ldlcode = $accounts;
            $stuse = $user->update();
            if ($stuse)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '邀请码设置成功！'
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('邀请码设置失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有找到该用户！')->responseError();
        }
    }


}
