<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Home\HomeController@index');
Route::get('/education/level', 'Home\EducationController@level');
Route::get('/education/info', 'Home\EducationController@info');
Route::get('/order/pay', 'Home\OrderController@pay');

//Auth::routes();


Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('content/{id?}', 'Home\ContentController@index');
Route::get('auth/github', 'Home\AuthController@github');

Route::any('auth/githubCallback', 'Home\AuthController@githubCallback');


//后台路由
Route::group(['middleware' => ['web']], function () {
    //后台登录路由
    Route::any('admin', 'Admin\LoginController@login');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['web','admin.login']], function(){
    //后台首页
    Route::get('/index', 'IndexController@index');
    Route::get('/quit', 'LoginController@quit'); //退出登录
    Route::any('/pass', 'IndexController@pass');

    //普通用户管理
    Route::get('user/users', 'UserController@ptUsers'); //禁用用户列表
    Route::any('user/search', 'UserController@search'); //用户搜索
    Route::post('user/upDown', 'UserController@upDown'); //用户开启或禁用
    Route::get('user/Reset', 'UserController@Reset'); //重置密码
    Route::post('user/ResetPass', 'UserController@ResetPass'); //重置密码 处理
    Route::any('user/Private', 'UserController@Privates'); //给用户发送私聊
    Route::resource('user', 'UserController');

    //配置项资源路由
    Route::get('config/putfile', 'ConfigController@putFile');
    Route::post('config/changeorder', 'ConfigController@changeorder');
    Route::post('config/changecontent', 'ConfigController@changecontent');
    Route::resource('config', 'ConfigController');

    //APP推送管理
    Route::resource('push', 'PushController');

    //版本管理
    Route::resource('version', 'VersionController');

    //广告管理
    Route::post('ad/changeorder', 'AdvertisementController@changeorder'); //排序
    Route::post('ad/upDown', 'AdvertisementController@upDown'); //开启和关闭
    Route::resource('ad', 'AdvertisementController');
    //广告显示位置管理
    Route::resource('adplace', 'AdplaceController');
    //广告跳转方式管理
    Route::resource('adskip', 'AdskipController');

    //分类管理
    Route::post('cate/changeorder', 'CateController@changeorder');
    Route::post('cate/upDown', 'CateController@upDown');
    Route::resource('cate', 'CateController');

    //出售管理
    Route::any('sell/Check', 'SellController@Check');
    Route::post('sell/search', 'SellController@search');
    Route::post('sell/updown', 'SellController@updown');
    Route::post('sell/delPic', 'SellController@delPic');
    Route::post('sell/changeorder', 'SellController@changeorder');
    Route::resource('sell', 'SellController');

    //意见反馈
    Route::post('findback/updown', 'FindbackController@updown');
    Route::any('findback/{id}', 'FindbackController@delete');
    Route::get('findback', 'FindbackController@index');

    //投诉管理
    Route::get('report', 'ReportController@index');
    Route::post('report/updown', 'ReportController@updown');

    Route::get('reportGroup', 'ReportController@reportGroup'); //群组投诉列表
    Route::post('reportGroup/updown', 'ReportController@reportGroupUpdown'); //群组投诉处理

    //地区
    Route::post('city/updown', 'CityController@updown');
    Route::post('city/ishot', 'CityController@ishot');
    Route::resource('cityList', 'CityController');

    Route::resource('ceditor', 'CeditorController');
});

Route::resource('reward', 'Home\RewardController');
Route::get('share/ldl/{user_id}', 'Home\ShareController@ldl');
