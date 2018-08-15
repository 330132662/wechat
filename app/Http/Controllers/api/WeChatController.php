<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/5
 * Time: 8:23
 */

namespace App\Http\Controllers\api;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\HttpException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Messages\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Cache\Simple\RedisCache;

/** 微信测试用   https://github.com/overtrue/laravel-wechat/blob/master/README.md
 * Class WeChatController
 * @package App\Http\Controllers
 */
class WeChatController extends ApiController
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
//        $this->app = app('wechat.open_platform');
        $this->app->server->push(function ($message) {
            return "欢迎关注 overtrue！";
        });

        return $this->app->server->serve();
    }

    public function __construct()
    {
//        $predis = app('redis')->connection()->client(); // connection($name), $name 默认为 `default`
//        $cache = new RedisCache($predis);
//        $this->app['cache'] = $cache;
//        $this->config = [
//            'app_id' => env("WECHAT_OPEN_PLATFORM_APPID"),
//            'secret' => env("WECHAT_OPEN_PLATFORM_SECRET"),
//            'token' => env("WECHAT_OPEN_PLATFORM_TOKEN"),
//            'aes_key' => env("WECHAT_OPEN_PLATFORM_AES_KEY")
//        ];
        $this->openPlatform = app('wechat.open_platform.default');//超哥推荐
//        dd($this->openPlatform);
//        $this->openPlatform = Factory::openPlatform($this->config);  //这种方法不会走 laravel-wechat 的缓存桥接逻辑，具体代码请参见：
//
//https://github.com/overtrue/laravel-wechat/blob/master/src/ServiceProvider.php#L89-L93
//        $this->openPlatform = \EasyWeChat::openPlatform(); // 开放平台
    }

    /**
     * 使用授权码换取接口调用凭据和授权信息
     */
    function handleAuthorize(Request $request)
    {
        return $this->openPlatform->handleAuthorize($request->authCode);
    }


    function getAuthUrl()
    {
        $this->openPlatform = app('wechat.open_platform.default');
//        dd($this->openPlatform['cache']); // 打印一下看看

        if (env("APP_ENV") == 'local') {
//            return '111';
            return $this->openPlatform->getPreAuthorizationUrl('https://wx.qyzx100.com/wechat/authcallback'); // 传入回调URI即可
        } else {
//            return '222';
            return $this->openPlatform->getPreAuthorizationUrl('https://taoshu.xinxihua.com/wechat/authcallback'); // 传入回调URI即可
        }


    }

    /*获取授权方的帐号基本信息*/

    function info(Request $request)
    {
        return $this->openPlatform->getAuthorizer($request->appId);
    }

    function authlist()
    {
        return $this->openPlatform->getAuthorizers(0, 20);
    }

    /*********                                ****************************/

    public function getTicket()
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


    /**     获取第三方平台的access_token
     * @return array
     */
    public function getToken()
    {
        // 获取 access token 实例
        $accessToken = $this->openPlatform->access_token;
        try {
            Log::info('获取token');
            return $accessToken->getToken();
        } catch (HttpException $e) {
            Log::error($e);
        } catch (InvalidArgumentException $e) {
            Log::error($e);
        } catch (InvalidConfigException $e) {
            Log::error($e);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            Log::error($e);
        } // token 数组  token['access_token'] 字符串
    }

    /*新增图文消息 */
    public function addNews(Request $request)
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






























