<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\UserVerification;
use App\Model\User;
use App\Transformer\UsersTransformer;
use App\Fcore\Controllers\ModelForm;
use App\Lib\Alipay\aop\AopClient;
use App\Lib\Alipay\aop\request\AlipaySystemOauthTokenRequest;
use App\Lib\Alipay\aop\request\AlipayUserInfoShareRequest;

class AlipayController extends JaseController
{
    use ModelForm;
    private $result;
    private $userverification;
    private $c;
    public function __construct()
    {
        $this->result = new Result();
        $this->userverification = new UserVerification();
        define("AOP_SDK_WORK_DIR", app_path()."/Lib/Alipay/");
        $this->c = new AopClient;
        $this->c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $this->c->appId = "2017071607774564";
        $this->c->rsaPrivateKey = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCaaN/wROGJFEDrnHDNihgXpvGjwiW6c3dm756lAYEoE96FV5jafVwMnXJ02y+aD5pTSx/3pxUbYUla7YCTXVKj2beOqk/xdF8qGcyvuKCrAR2ZqOhxvffhvbG7vQ0UOcn93i9AMnOrMMUFL3+fXcKaaqdZUGz7/sW6ibb1wYPeLITuZvSrF95dNF5m13qmZBPxTMPP4uuxrhaEH1X2dttVWYZF9sqt6o5vlALMmFmD0FLQnYyqoX/6OBypJoxLRtWXaGFjZdPty10eb9IZrwW9ODK2IoaFAHXuq0OHpUxCUyMuoObannoyVgDzjzeWz2WFEcfj4bgn8PNziXte9pYNAgMBAAECggEAHG7bbb8BWOCl47z2+KJJNXHEZUdG80J35jiRmgjg46RlAWDUAMe8v4XuoZ4K8e+Vl2jqBgx6UoRjoZ7CYmB46zsYFek+t1OagoIlsuXokBSwgq8bvnYgwyzPIab6VZMeouTbBpNDwW5FmWLZuVrmjtC86DFrICwV9PeO/UoZIjv+0FQzSBnogzH8K6i84+SY14Rqz5k1t8PXVQNHNAlTE0QpkblVuCMhSgLmRiKHu0vUVOD3o1ecOnSA+WbpOXfiC6XapibpkGGe78lDkhAPxiSParP1znyvww2VH9xkpenwMk4peNyxd1uXu++n3Jp6EgWNBh6oEaCI3PLeA9DVgQKBgQDcli16LM2SSWpe7h2MCaHgIaXqUp3Bw6GZzP0gUCKHWF1Ba2k+7YJVyA/ca/N9ZcI1KDx/Xde+tv4Mk2TiKCxtH2p8hIrr7lNb+s8fLZHOZgJLxWds/XUY1Cxh1/WpfXYHWjGmGnus17kUr/LG/8F4+JBWGmGW0VB/3z6R3Dl5+QKBgQCzMukL7JK/icQSW9tv6gcWR7D7g82p3eTLnC4Vlwt1aFcLHS90z2hyE0ZfCXvAaq+u00lztdn4iulhVVA+9dpwiib/rwzON75G26smQEutJ253NIqNpAB/Tfj7BswirXlydYT62k83KfhUDHhMA1yGi6pHXis7Nl65CsCf4vxhtQKBgQDX719neVJoANxTP6/G9Wr0eJvtraBnHPYmFCg6qJeUfKkVsGsfwetTw/va4AZE3AdoeBH48MmRFZvOfb9FMOSEkjtw12MTIIOTyAtXzwkrzmoy+HSNmfQ2MQWdZoZCu+F9wwpVOxmUkrzIhpEXwygHHvRUhZDAzfiKpOSgbsAJKQKBgE2QeL0XjkFn0T2dEvNzGdQz/dAwMcIX8KONG4lu0p/kJOEDpfnqmKvf2fLi+PTFePu0KrPx/8IL5o6hzdUit4VE1zKOw30zNaIYDRHGfLsbOT5RuLMsTKbNyjplq9BKxCmd4oxuGCGpgfpnV16XieIW7AJuKUo8820m0U+jgViNAoGAKPorpRDDO9MIcg4Xzsf/YE2RtzdMI9T6/P1ks8SIXie2C3Rv8gm22IvOz0coYk1X1PAYDT/Od9+5scmDTV5epP7nZLd4G1+BBAuYD2LF02uqjz2cGoJI2JQRe3lEk14edqKpSml+fpAUqigrat0cL2zWkZFhyw1ZWmntQPupny4=' ;
        $this->c->format = "json";
        $this->c->charset= "UTF-8";
        $this->c->signType= "RSA2";
        $this->c->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmmjf8EThiRRA65xwzYoYF6bxo8IlunN3Zu+epQGBKBPehVeY2n1cDJ1ydNsvmg+aU0sf96cVG2FJWu2Ak11So9m3jqpP8XRfKhnMr7igqwEdmajocb334b2xu70NFDnJ/d4vQDJzqzDFBS9/n13CmmqnWVBs+/7Fuom29cGD3iyE7mb0qxfeXTReZtd6pmQT8UzDz+Lrsa4WhB9V9nbbVVmGRfbKreqOb5QCzJhZg9BS0J2MqqF/+jgcqSaMS0bVl2hhY2XT7ctdHm/SGa8FvTgytiKGhQB17qtDh6VMQlMjLqDm2p56MlYA8483ls9lhRHH4+G4J/Dzc4l7XvaWDQIDAQAB';

    }



    public function bind (Request $request)
    {
        //include app_path() . '/Lib/Alipay/aop/AopClient.php';
        //include app_path() . '/Lib/Alipay/aop/request/AlipaySystemOauthTokenRequest.php';
        $tokenRequest = new AlipaySystemOauthTokenRequest();
        $tokenRequest->setGrantType('authorization_code');
        $tokenRequest->setCode($request->get('auth_code'));
        try {
            $tokenResponse = $this->c->execute($tokenRequest);
            app('log')->info('fullurl==>'.$request->fullUrl()."\nhttpmethod==>".$request->getMethod()."\ntokenResponse==>".\json_encode($tokenResponse, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e){
            return $this->result->setStatusMsg('error')->setStatusCode(605)->setMessage($e->getMessage())->responseError();
        }
        $shareRequest = new AlipayUserInfoShareRequest();
        try {
            $shareResponse = $this->c->execute($shareRequest, $tokenResponse['alipay_system_oauth_token_response']['access_token']);
            app('log')->info('fullurl==>'.$request->fullUrl()."\nhttpmethod==>".$request->getMethod()."\nshareResponse==>".\json_encode($shareResponse, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e){
            return $this->result->setStatusMsg('error')->setStatusCode(605)->setMessage($e->getMessage())->responseError();
        }

        $user_id = $request->item['uid'];
        $user = User::find($user_id);
        if ($user)
        {
            $user->alipay_account = $shareResponse['alipay_user_userinfo_share_response']['email'];
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

    public function unbind (Request $request) {
        return $this->userverification->unSetAlipayAccount($request);
    }

    public function sign(Request $request) {
        $all  = $request->all();
        if(empty($all['sign_type'])) {
            $all['sign_type'] = 'RSA2';
        } else {
            $all['sign_type'] = $all['sign_type']!='RSA' && $all['sign_type']!='RSA2' ? 'RSA2' : $all['sign_type'];
        }
        ksort($all);
        $sign = $this->c->generateSign($all, $all['sign_type']);
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object'=>['input'=>$all, 'sign'=>$sign]
        ]);
    }
}
