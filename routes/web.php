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
Route::get('/', 'WeappsController@index');
Route::get('home', 'WeappsController@index');
Route::resource('templates', "TemplatesController");
Route::resource('articles', "ArticlesController");
Route::resource("users", "UsersController");//注册界面
Route::resource("weapps", "WeappsController");// 路由对应关系 https://laravel-china.org/courses/laravel-essential-training-5.5/584/according-to-the-users-information
Route::resource('products', 'ProductsController');
Route::resource('services', 'ServicesController');
Route::post('file/upload', "FilesController@upload")->name("file/upload");

Route::match(['get', 'post'], 'password/reset', 'UsersController@resetpassword')->name('password.request');
Route::resource('users', 'UsersController');

XXH::routes();

//获取已授权的授权方列表
$wechat = 'WeChatController';
$code = 'CodeController';
Route::get('wechat/authlist', $wechat . '@authlist');
Route::get('wechat/authcallback', $wechat . '@authCallback')->name('wechat/authcallback');
Route::get('wechat/templist', $code . '@templist');
Route::get('wechat/commit', $code . '@commit')->name('wechat/commit');
Route::get('wechat/release', $code . '@release')->name('wechat/release');
