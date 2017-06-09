<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    public function github ()
    {
        $rand = rand(10,100);
        $state = md5($rand);
        $url = "https://github.com/login/oauth/authorize?client_id=27876e747dc70bab273d&state=$state";
        return redirect($url);
    }


    public function githubCallback (Request $request)
    {
        $code = Input::get('code');
        $state = Input::get('state');
        $url = "https://github.com/login/oauth/access_token?client_id=27876e747dc70bab273d&client_secret=4f87110396efea9b59746b14afac81cd18d1e993&code=$code&state=$state&redirect_uri=http://bug.juda-media.com/auth/githubCallback";
        $html = $this->postCurl($url);
        $user_url = "https://api.github.com/user?$html";
        $user_arr = $this->postCurl($user_url);
        return $user_arr;
    }

    public function postCurl ($url, $option = array(), $header = array(), $type = 'GET')
    {
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE ); // 对认证证书来源的检查
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE ); // 从证书中检查SSL加密算法是否存在
        curl_setopt ( $curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)' ); // 模拟用户使用的浏览器
        if (! empty ( $option )) {
            $options = json_encode ( $option );
            curl_setopt ( $curl, CURLOPT_POSTFIELDS, $options ); // Post提交的数据包
        }
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header ); // 设置HTTP头
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
        curl_setopt ( $curl, CURLOPT_CUSTOMREQUEST, $type );
        $result = curl_exec ( $curl ); // 执行操作
        //$res = object_array ( json_decode ( $result ) );
        //$res ['status'] = curl_getinfo ( $curl, CURLINFO_HTTP_CODE );
        //pre ( $res );
        curl_close ( $curl ); // 关闭CURL会话
        return $result;
    }
}
