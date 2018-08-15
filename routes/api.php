<?php

use EasyWeChat\Factory;
use EasyWeChat\OpenPlatform\Server\Guard;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('articles', "api\ArticlesController", ["only" => ['index', 'show']]);
Route::resource('products', "api\ProductsController", ["only" => ['index', 'show']]);
Route::resource('services', "api\ServicesController", ["only" => ['index', 'show']]);
Route::resource('weapps', "api\WeappsController", ["only" => ['show']]);
$wechatApi = "api\WeChatController";
Route::any('/wechat', $wechatApi . '@serve');
Route::any('/getAuthUrl', $wechatApi . '@getAuthUrl');
// 处理事件
Route::any('open', $wechatApi . '@openplatform');
Route::get('info', $wechatApi . '@info');
Route::get('wechat/index', $wechatApi . '@index');
Route::get('wechat/getpayload', $wechatApi . '@getPayload');

Route::any('open-platform', function () {
    $config = [
        'app_id' => env("WECHAT_OPEN_PLATFORM_APPID"),
        'secret' => env("WECHAT_OPEN_PLATFORM_SECRET"),
        'token' => env("WECHAT_OPEN_PLATFORM_TOKEN"),
        'aes_key' => env("WECHAT_OPEN_PLATFORM_AES_KEY")
    ];
    $openPlatform = Factory::openPlatform($config);
    $server = $openPlatform->server;
    $server->push(function ($message) use ($openPlatform) {
    }, Guard::EVENT_AUTHORIZED);
    $server->push(function ($message) {
    }, Guard::EVENT_UPDATE_AUTHORIZED);
    $server->push(function ($message) {
    }, Guard::EVENT_UNAUTHORIZED);
    $server->push(function ($message) {
    }, Guard::EVENT_COMPONENT_VERIFY_TICKET);
    return $server->serve();
});

//获取已授权的授权方列表
Route::get('wechat/authlist', $wechatApi . '@authlist');
Route::get('wechat/getcomponenttoken', $wechatApi . '@getToken');

//使用授权码换取接口调用凭据和授权信息
Route::get('wechat/handleAuthorize', $wechatApi . '@handleAuthorize');
//获取授权方的帐号基本信息
//Route::get('wechat/authinfo', $wechatApi.'@');
//获取授权方的选项设置信息
//Route::get('wechat/infoget', $wechatApi.'@');
//设置授权方的选项信息
//Route::get('wechat/infoset', $wechatApi.'@');
$codeApi = 'api\CodeController';
/*小程序*/
Route::post('wechat/codecommit', $codeApi . '@codeCommit')->name('wechat/codecommit');  /*提交代码*/
Route::get('wechat/templist', $codeApi . '@templist');
Route::get('wechat/getpage', $codeApi . '@getPageConfig');
Route::get('wechat/getcategory', $codeApi . '@getCategory');
Route::post('wechat/submitaudit', $codeApi . '@submitAudit');
Route::get('wechat/getlateststatus', $codeApi . '@getLatestAuditStatus');
Route::get('wechat/getqrcode', $codeApi . '@getQrCode');
