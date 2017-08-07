<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('/test', 'HomeController@test');

});



Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    $attributes = ['middleware' => 'admin.permission:allow,administrator,manager'];
    $router->group($attributes, function ($router) {
        $router->get('education/levelNext', 'EducationController@levelNext');
        $router->resource('education', 'EducationController');
        $router->resource('config', 'ConfigController');
        $router->resource('user', 'UserController');
        $router->resource('user/{id}/privatechat', 'UserController@privateChat');
        $router->get('cate/cindex', 'CateController@cindex');
        $router->resource('cate', 'CateController');
        $router->resource('sell', 'SellController');

    });

});