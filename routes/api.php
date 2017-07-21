<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    /*分组不需要任何验证*/
    $api->group(['namespace' => 'App\Http\Controllers\Api'], function ($api) {
        $api->post('register', 'RegisterController@register'); //用户注册
        $api->post('SendSms', 'RegisterController@SendSms'); //用户注册发送短信验证码
        $api->post('Login', 'RegisterController@Login'); //用户登陆
        $api->post('thirdParty/login', 'RegisterController@thirdPartyLogin'); //第三方登陆
        $api->post('login/binding', 'RegisterController@binding'); //第三方绑定
        $api->post('resetPass', 'RegisterController@resetPassword'); //用户重置密码
        $api->get('share/{sellId}', 'HomeController@share'); //分享
        $api->get('version/{type}', 'HomeController@version'); //监察版本
        /*f分类*/
        $api->get('cate/industry', 'CateController@industry');
        $api->get('cate', 'CateController@index');
        $api->resource('test', 'TestController');

        /*城市列表*/

        $api->get('city', 'HomeController@cityList');

        $api->resource('area', 'AreaController');




        /*分组需要token验证*/
        $api->group(['middleware' => 'user.auth'], function ($api) {
            $api->post('user/loginOut', 'RegisterController@loginOut'); //用户退出 token
            $api->get('user/me', 'RegisterController@getAuthenticatedUser'); //获取用户信息 当登录成功后 使用token获取用户信息
            $api->post('resetPhone', 'RegisterController@resetPhone'); //更换手机号

            /*用户信息*/
            $api->post('user/changeFace', 'UserController@changeFace'); //用户修改头像  使用token 和 face 去修改用户头像
            $api->post('user/changeSex', 'UserController@changeSex'); //用户修改性别 1男2女
            $api->post('user/changeSign', 'UserController@changeSign'); //用户修改签名
            $api->post('user/changeArea', 'UserController@changeArea'); //用户修改区域
            $api->post('user/changeNickname', 'UserController@changeNickname'); //用户修改区域
            $api->get('userInfo', 'UserController@userInfo'); //查看用户信息
            $api->get('userInfoForPhone', 'UserController@userInfoForPhone'); //查看用户信息 for 用户手机号
            $api->post('userSetNotes', 'UserController@SetNotes'); //给好友设置备注
            $api->post('userSetBackground', 'UserController@userSetBackground'); //设置朋友圈背景
            $api->resource('usercate', 'UserCateController'); //用户的品类相关
            $api->resource('user/check', 'UserController@check'); //用户信息完整检查
            //$api->post('user/setAlipayAccount', 'UserController@setAlipayAccount'); //绑定支付宝账号
            //$api->post('user/unSetAlipayAccount', 'UserController@unSetAlipayAccount'); //绑定支付宝账号


            /*我要买*/
            $api->post('business', 'BusinessController@Buystore'); //我要买
            /*我要卖*/
            $api->post('businessSell', 'BusinessController@Sellstore'); //我要卖
            /*我要买推荐*/
            $api->get('businessRecommend', 'BusinessController@businessRecommend'); //我要买推荐
            /*我要卖推荐*/
            $api->get('sellRecommend', 'BusinessController@sellRecommend'); //我要卖推荐
            /*买卖点赞*/
            $api->get('thumbsUp', 'BusinessController@thumbsUp'); //出售买卖点赞
            /*买卖取消点赞*/
            $api->get('thumbsUpOff', 'BusinessController@thumbsUpOff'); //出售买卖取消点赞
            /*发现列表*/
            $api->get('findSell', 'FindsellController@index'); //发现列表
            /*发现详情 - 买卖*/
            $api->get('findSellInfo', 'FindsellController@findSellInfo'); //发现详情
            /*发现详情 - 朋友圈*/
            $api->get('findSellInfos', 'FindsellController@findSellInfos'); //发现详情 - 朋友圈
            /*编辑权限*/
            $api->post('findSell/changeAuth', 'FindsellController@findSellChangeAuth'); //我要买卖编辑权限
            /*我要买卖编辑查看*/
            $api->get('findSell/edit', 'FindsellController@findSellEdit'); //我要买卖编辑查看
            /*我要买卖编辑*/
            $api->post('findSell/Update', 'FindsellController@findSellUpdate'); //我要买卖编辑
            /*我要买卖删除*/
            $api->get('findSell/Delete', 'FindsellController@findSellDelete'); //我要买卖删除

            /*评论*/
            $api->post('common', 'CommonController@common'); //添加评论

            /*意见反馈*/
            $api->post('feedback', 'FindbackController@feedback'); //意见反馈

            /*发布朋友圈*/
            $api->post('friends', 'FriendsController@store'); //发布朋友圈

            /*我的朋友圈*/
            $api->get('friendsList', 'FriendsController@friendsList'); //我的朋友圈

            /*别人的主页*/
            $api->get('outherFriends', 'FriendsController@outherFriends');  //其他人的主页

            /**搜索*/
            $api->get('searchBusiness', 'SearchController@searchBusiness');  //我要买搜索
            $api->get('searchBusiness/sell', 'SearchController@searchBusinessSell');  //我要卖搜索
            $api->get('searchPeople', 'SearchController@searchPeople');  //好友搜索

            /*了当了群管理*/
            $api->post('group/create', 'GroupController@create'); //创建群
            $api->get('group/info', 'GroupController@groupInfo'); //我的群组 的信息
            $api->post('group/edit/groupname', 'GroupController@groupEditGroupname'); //群组修改昵称
            $api->post('group/edit/describe', 'GroupController@groupEditDescribe'); //群组修改描述
            $api->post('group/edit/group_face', 'GroupController@groupEditGroupFace'); //群组修改头像
            $api->get('group/members', 'GroupController@members'); //群组全部成员
            $api->get('group/search', 'GroupController@search'); //搜索群

            /*群发消息*/
            $api->post('send/users', 'SendController@sendUsers'); //群发消息
            $api->get('send/surplusNum', 'SendController@surplusNum'); //我今天剩余群发次数
            $api->post('send/uploadFile', 'SendController@uploadFile'); //文件上传

            /*投诉*/
            $api->post('report', 'ReportController@report');
            $api->post('reportGroup', 'ReportController@reportGroup'); //群投诉

            /*获取七牛token*/
            $api->get('getToken', 'HomeController@getToken');

            /*监察手机号*/
            $api->post('checkPhone', 'HomeController@checkPhone');

            /*统计我发布的买卖和朋友圈*/
            $api->get('totalSell', 'HomeController@totalSell');

            $api->put('alipay/bind', 'AlipayController@bind');
            $api->put('alipay/unbind', 'AlipayController@unbind');
            $api->post('alipay/sign', 'AlipayController@sign');
            $api->get('alipay/auth', 'AlipayController@auth');
        });


        $api->group(['middleware' => ['user.auth', 'fcore']], function ($api) {
            $api->resource('useraddr',  'UserAddrController');      //用户的地址相关
            $api->resource('usershare', 'UserShareController');     //分享相关
            $api->resource('reward', 'RewardController');           //抽奖相关
            $api->resource('userreward', 'UserRewardController');   //抽奖奖励相关
            $api->resource('usermoney', 'UserMoneyController');     //账单明细
            $api->resource('userpoints', 'UserShareController');    //积分相关
            $api->resource('withdraw/info', 'WithdrawController@info');       //提现页面数据
            $api->resource('withdraw', 'WithdrawController');       //提现相关
        });
    });
});

