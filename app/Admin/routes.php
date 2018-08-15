<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $weapp = 'WeappsAdmin';
    $router->get('weapps/index', $weapp . '@index');
    $router->get('/weapps/{weapp}/edit', $weapp . '@edit');

});
