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
        $router->get('education/level2', 'EducationController@level2');
        $router->get('education/level3', 'EducationController@level3');
        $router->resource('education', 'EducationController');
        $router->resource('config', 'ConfigController');
        $router->resource('user', 'UserController');
        $router->resource('user/{id}/privatechat', 'UserController@privateChat');
        $router->get('cate/cindex', 'CateController@cindex');
        $router->resource('cate', 'CateController');
        $router->resource('sell', 'SellController');
        $router->resource('school', 'SchoolController');
    });

});


Route::get('upload/{one?}/{two?}/{three?}/{four?}/{five?}/{six?}/{seven?}/{eight?}/{nine?}',function(){
    \App\Util\ImageRoute::imageStorageRoute();
});