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

class AlipayController extends Controller
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
        //$this->c->rsaPrivateKey = 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC2n8qFe/gif9ZZdjDEHoOkABWHZLRfNR5lujhWix/BYW9K1BpcPJz0qFO3c47GX/C8sQOjUO2dA7Lx1vaIAgFWDfSGuQ76kW7H/idpOO4VmNMSYs4cszfhlygpX+GzZH9pmrv3MhgJmaIMaNYsZu554zTAQnNieNVAQhn17zaXEi3dWnn3rS10hwVxtL8j2C6RAJyYehi0xGWFcHbZbsnjxleTi8mXahtZOj44jq+etYDmxTBWtl+4oliRpD4uXyrlw9Wc83kOg53tsFlCMN7o+Ip9v49125M6hqwjObO2KbG4mTFPEF26nRS93+61FhXOho/IQGB4Nvcp/GtZGd+lAgMBAAECggEAOMYYWcMecus3IfzCUFYEKrnz93FxOZ/z4UAxP4ZkyuOVi7fAsigNy2mBDSkZm8Y7uw10yhLgq814lgJ0BfhJng0clb12UKNhwz7Z1bZjHcey5qZc2UNF/hXV4Hb0XabCjXwTL1gdbO6cGCuHWapuPOpLffGqmVRjR2TFWVWSrQfss9MDf0ts5o0gQ3dv34g3AhRSE3JBzH8ubC9j2vYd0oqgOuGcCp8aQ2388yUvGvD8pPreaPNZ9QT9uI4iQamXqi+9Z2b2W7rcAX+WI+fQNO/Gb1nXbGSDo6N0HyFbKQ8JcOa7CsMn6a70W/59Kaq0qizBP0/dR0Xv3xurEBc0wQKBgQDuPtrn26zAkRLgNxQLYJn/cQf8yo7AgYJfdH/zS1wiVtnF/q8p3JhRI1vxmBZKLaITKCAQGVHtdwudc0LH+R9E+wLxWnERbI3E6nr7XXpEe8ZiTJ+2BrQC/Ou9zV8t69o0hWvp38/1QKMDPg7aw0xcv1GYwU+5ie4vumjmxPmrLQKBgQDEO9Ds4BR6CCSshZgw/637IIhQ5h2J7FQbFTbG7JzPTEivkV7paIEPnOxxWk4zITYdBwAnmu3yVumM4pKubTDnZwd2RroFSj25F8UdhD1S+OtZLm6Y5bGgrH2OlRzP5MUd23IV/CdFAeT700NUbSDE71L6Pi/lpCKkcRCQHcjxWQKBgQCGvtH8VIguGV79pOkXTjmK+Zi05rM6OYVNC4zaQ4MGgOyGSPc0y+jx6vezensQ1SJXh1grAscNcUkGcgwRIdsHnpgZfTd5zVWZS6zmwgXG+eJH8mJaBwyErBuAq8jrJfuxvMw96DGr2jTYPp8UJ2TnB+XUaXD31QT7xYhTDJDQuQKBgQC17VrR8SEqzdfqVmT1sGVFODEJB36FOyPyg+Vn4T+z9F5C3vxmWqukpPCUWou7XWQE31OmZD2L+kXGhtxEeBjpwBwr7IBTnTPCISZvKgWMohfXc3O3ruPSOXiiMt4mY0rYVnXbqg1iLdJ0IbX/VLkXu2VreTIArf4sBGK9qc986QKBgBRjrvWSXEzMxe5ihX2E6W/sWLGKr6Xmc5PT/Dyo62pJcG/n/lBhhnfxcnUANbDuR83N6lnN9SKAqfff/iFK4G/LQ4z2zZSAfmhxshQ4R+knDL5pEfKLkyGPm5oOSCmdYB/2nYAMcZvkgoeaCPepWpJY02ur0EBhDcOQGaAGq3C1';
        $this->c->format = "json";
        $this->c->charset= "UTF-8";
        $this->c->signType= "RSA2";
        $this->c->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmmjf8EThiRRA65xwzYoYF6bxo8IlunN3Zu+epQGBKBPehVeY2n1cDJ1ydNsvmg+aU0sf96cVG2FJWu2Ak11So9m3jqpP8XRfKhnMr7igqwEdmajocb334b2xu70NFDnJ/d4vQDJzqzDFBS9/n13CmmqnWVBs+/7Fuom29cGD3iyE7mb0qxfeXTReZtd6pmQT8UzDz+Lrsa4WhB9V9nbbVVmGRfbKreqOb5QCzJhZg9BS0J2MqqF/+jgcqSaMS0bVl2hhY2XT7ctdHm/SGa8FvTgytiKGhQB17qtDh6VMQlMjLqDm2p56MlYA8483ls9lhRHH4+G4J/Dzc4l7XvaWDQIDAQAB';
        $this->c->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAlkR+whMRcYIybSBl5b1O4Gyv2Y00th/fxn+4tpCRkU1OmGo6l3UESg319yJCXWfIFIHRVCe+11JKiV7OyTYBVX4wC83ekqDVrVwGNBziU0ZrE2gerDRihX66xGGCs0w1TIhQsoawCH1hd61VOz6ABWp3l7yN8WM2KrXkl0OyGC2PVOO01eF9Y8cojPAm3nvOts/056C6X+o5Le9UTZ5m/AGAWOf9u3BBigG8lDrrG1P83+QON6irZcjgI55TJl9QtiNsb9W22xfbJzWVTS1xR4R1EfrkUyE4Cbw2peJSkUqIedZn2vndIN1aQ1G0uXp237rJEQiwRX6vKtm7/RpaeQIDAQAB';
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
            $shareResponse = $this->c->execute($shareRequest, $tokenResponse->alipay_system_oauth_token_response->access_token);
            app('log')->info('fullurl==>'.$request->fullUrl()."\nhttpmethod==>".$request->getMethod()."\nshareResponse==>".\json_encode($shareResponse, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e){
            return $this->result->setStatusMsg('error')->setStatusCode(605)->setMessage($e->getMessage())->responseError();
        }

        $user = User::where('alipay_account', $shareResponse->alipay_user_userinfo_share_response->user_id)->first();
        if(!empty($user)) {
            return $this->result->setStatusMsg('error')->setStatusCode(605)->setMessage('该支付宝账户已经绑定过了')->responseError();
        }
        $user_id = $request->item['uid'];
        $user = User::find($user_id);
        if ($user)
        {
            $user->alipay_account = $shareResponse->alipay_user_userinfo_share_response->user_id;
            $user->alipay_nickname = $shareResponse->alipay_user_userinfo_share_response->nick_name;
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
            'object'=>['input'=>$all, 'sign'=>urlencode($sign)]
        ]);
    }

    public function auth(Request $request) {
        $all = [
            'biz_type' => 'openservice',
            'scope' => 'kuaijie',
            'product_id' => 'APP_FAST_LOGIN',
            'auth_type' => 'AUTHACCOUNT',
            'apiname' => 'com.alipay.account.auth',
            'sign_type' => 'RSA2',
            'pid' => '2088901865907742',
            'app_id' => '2017071607774564',
            'app_name' => 'mc',
            'target_id' => 'kkkkk091125',
            'method' => 'alipay.open.auth.sdk.code.get'
        ];
        if(empty($all['sign_type'])) {
            $all['sign_type'] = 'RSA2';
        } else {
            $all['sign_type'] = $all['sign_type']!='RSA' && $all['sign_type']!='RSA2' ? 'RSA2' : $all['sign_type'];
        }
        ksort($all);
        $sign = $this->c->generateSign($all, $all['sign_type']);
        $all['sign'] = $sign;
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => http_build_query($all)
        ]);
    }
}
