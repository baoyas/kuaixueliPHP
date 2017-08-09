<?php

namespace App\Http\Controllers\Home;

use Cache;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\Sms;

class SmsController extends Controller
{
    public function index (Request $request)
    {
        //return view('reward/index')->header('Cache-Control', 'no-store');
        return Response::view('reward/index')->header('Cache-Control', 'no-store');
    }

    public function send(Request $request) {
        $mobile = $request->input("mobile");
        $vcode  = $request->input("vcode");
        if(empty($mobile) || strlen($mobile) != 11) {
            return $this->response(NULL, 1002, '无效的手机号码');
        }
        $numCheckByMobileKey = "sms_send_num_check_by_mobile".$mobile;
        $numCheckByIpKey = "sms_send_num_check_by_ip".$request->getClientIp();
        $intervalCheckByMobileKey = "sms_send_interval_by_mobile".$mobile;
        if(Cache::get($numCheckByMobileKey)===null) {
            Cache::put($numCheckByMobileKey, 0, 60*2);
        }
        if(Cache::get($numCheckByIpKey)===null) {
            Cache::put($numCheckByIpKey, 0, 60*2);
        }
        $numCheckByMobile = Cache::increment($numCheckByMobileKey);
        $numCheckByIp     = Cache::increment($numCheckByIpKey);
        $verfiyCode = Cache::get("vcode_by_mobile{$mobile}");
        if($verfiyCode && $vcode && $verfiyCode==$vcode) {
        } elseif ($numCheckByIp>300) {
            return $this->response(NULL, 1006, '验证码发送次数过多');
        } elseif ($numCheckByMobile>20) {
            $interval = Cache::get($intervalCheckByMobileKey);
            if($interval===null) {
                Cache::put($intervalCheckByMobileKey, time(), 60*2);
                return $this->response(NULL, 1006, '请等180秒后再试');
            } elseif(time()-$interval>=180) {
                Cache::forget($intervalCheckByMobileKey);
            } else {
                return $this->response(NULL, 1006, '请等'.(180-(time()-$interval)).'秒后再试');
            }
        }
        Cache::forget("vcode_by_mobile{$mobile}");
        $code = mt_rand(100000, 999999);
        $sms = new Sms();
        $result = $sms->send($mobile, '您的注册验证码是：'.$code);
        if(isset($result['code']) && strcmp($result['code'], '0')===0) {
            Cache::put("sms_".$mobile, $code, 60*2);
            return $this->response();
        }

        return $this->response(NULL, 1002, '发送失败');
    }
}