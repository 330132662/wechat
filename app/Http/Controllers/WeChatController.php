<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/5
 * Time: 8:23
 */

namespace App\Http\Controllers;

use App\Models\Weapp;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Cache\Simple\RedisCache;

/** 微信测试用   https://github.com/overtrue/laravel-wechat/blob/master/README.md
 * Class WeChatController
 * @package App\Http\Controllers
 */
class WeChatController extends Controller
{
    protected $openPlatform;
    protected $app;
    protected $config;

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        // Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

//        $this->app = app('wechat.official_account');
        $this->app = app('wechat.open_platform');
        $this->app->server->push(function ($message) {
            return "欢迎关注 overtrue！";
        });

        return $this->app->server->serve();
    }

    public function __construct()
    {
        $predis = app('redis')->connection()->client(); // connection($name), $name 默认为 `default`
        $cache = new RedisCache($predis);
        $this->app['cache'] = $cache;
        $this->openPlatform = app('wechat.open_platform.default');//超哥推荐

    }

    /* 授权回调入口  这里路由改动了要去 开放平台的后台改 */
    function authCallback(Request $request)
    {
        $auth_code = $request->auth_code;
        $info = $this->openPlatform->handleAuthorize($auth_code);
        $weappid = Session::get('weapp_auth');
//        将id和appid绑定 起来
        $weapp = Weapp::find($weappid);
        $weapp->appid = $info['authorization_info']['authorizer_appid'];
        $weapp->update();
        return redirect(url('weapps/' . $weappid . '/edit'));


    }


    /*获取授权方的帐号基本信息*/

    function info(Request $request)
    {
        return $this->openPlatform->getAuthorizer($request->appId);
    }

    function authlist()
    {
        $mp = $this->openPlatform->getAuthorizers(0, 20);
        return view('weapp/index', compact('mp'));
    }

    /* 获取小程序模板列表*/
    function templist(Request $request)
    {
        $miniProgram = $this->openPlatform->miniProgram($request->appid, $request->refreshtoken);
    }


    /*********                                ****************************/

    private
        $appId = '';
    private
        $appSecret = '';

    public
    function getTicket()
    {

        $accesstoken = $this->getToken();
        $data['accesstoken'] = $accesstoken;
        $token = json_decode($accesstoken); //对JSON格式的字符串进行编码
        $data['token'] = $token;
        $t = get_object_vars($token);//转换成数组
        $access_token = $t['access_token'];//输出access_token
        $jsapi = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $access_token . "&type=jsapi");
        $jsapi = json_decode($jsapi);
        $j = get_object_vars($jsapi);
        $jsapi = $j['ticket'];//get JSAPI
        return $jsapi;

    }


    public
    function getToken()
    {
        return file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appId . "&secret=" . $this->appSecret . "");//获取access_token
    }

    /*新增图文消息 */
    public
    function addNews(Request $request)
    {

        $app = app('wechat.official_account');
        $result = $app->material->uploadThumb("images/fa9e99dce6a114bc77e929f9ed94a959.jpg");//TODO  等简文出log  换成logo 。
        //        $result = $app->material->uploadThumb("https://mmbiz.qpic.cn/mmbiz_jpg/Eg7oAwhk8vXIyzwwSgH0FvicLmC5MqJES2tRUXCQKyaiatGdo9aFFjePqI0iamr65J7W4Y6Mclyy6gibPdIN98QpLA/0?wx_fmt=jpeg");
        if (!$result) {
            return response('图片上传失败', 500);
        }
        // 上传单篇图文
        $article = new Article([
            'title' => $request->title,
            'thumb_media_id' => $result['media_id'],
//            'thumb_media_id' => 'kMYCz9keGarPMs3U4GqCcq5vnrUTFOxcCNqnv2gbYJI',
            'content' => $request->contents,
            'source_url' => $request->content_source_url,
            'show_cover' => $request->show_cover_pic,
        ]);
        $result = $app->material->uploadArticle($article);
        return $result;

// 或者多篇图文
//        $app->material->uploadArticle([$article, $article2, ...]);
    }


}






























