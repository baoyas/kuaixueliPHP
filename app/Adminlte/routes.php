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
        $router->resource('ceditor', 'CeditorController');
        $router->resource('config', 'ConfigController');
        $router->resource('user', 'UserController');
        $router->resource('user/{id}/privatechat', 'UserController@privateChat');
        $router->resource('cate', 'CateController');
        $router->resource('sell', 'SellController');

    });

});