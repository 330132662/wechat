<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/3
 * Time: 8:47
 */

namespace App\Http\Controllers;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\HttpException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Cache\Simple\RedisCache;

/**　小程序代码管理
 * Class CodeController
 * @package App\Http\Controllers\api
 */
class CodeController extends Controller
{
    protected $openPlatform;
    protected $app;
    protected $config;


    public function __construct()
    {
        $predis = app('redis')->connection()->client(); // connection($name), $name 默认为 `default`
        $cache = new RedisCache($predis);
        $app['cache'] = $cache;
        $this->config = [
            'app_id' => env("WECHAT_OPEN_PLATFORM_APPID"),
            'secret' => env("WECHAT_OPEN_PLATFORM_SECRET"),
            'token' => env("WECHAT_OPEN_PLATFORM_TOKEN"),
            'aes_key' => env("WECHAT_OPEN_PLATFORM_AES_KEY")
        ];
//        $this->openPlatform = Factory::openPlatform($this->config);
        $this->openPlatform = app('wechat.open_platform.default');//超哥推荐

    }

    /**  12_aAf6aKz0a4fpUV7V776zoYgTugBNMDZMEOC4c-RDervm4vIH6WSh3zDo7IUIZofwpgohaz54jxEA89QpRwYDY_kBtaHpWvzMSUdZKE_uErd0R8Qm7ZT82B7apmsKdbiabOQHhvo9QfxkPjfhSAAdAJAEMM
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

    /**  替换指定小程序的模板
     *  overtrue\wechat\src\OpenPlatform\Authorizer\MiniProgram\Code\Client.php
     */
    /*function codeCommit(Request $request)
    {
        $templateid = $request->templateid;
        $extjson = $request->extjson;
        $version = $request->version;
        $desc = $request->desc;
//        获取授权方实例
        $miniProgram = $this->openPlatform->miniProgram($request->appid, $this->getToken());
        try {
            $miniProgram->code->commit($templateid, $extjson, $version, $desc);
        } catch (InvalidConfigException $e) {
            Log::error('替换指定小程序的模板-错误', $e);
        }

    }*/


    /* 获取小程序模板列表*/
    function templist()
    {

//        $miniProgram = $this->openPlatform->miniProgram($request->appid, $this->getToken());
        $result = $this->openPlatform->code_template->list();

        if ($result['errcode'] == 0) {
            $templates = $result ['template_list'];
        } else {
            $templates = [];
        }
        return view('code/templates', compact('templates'));
    }

    function commit(Request $request)
    {
        $miniProgram = $this->getMiniProgram($request);
        try {
            $categorys = $miniProgram->code->getCategory();
        } catch (InvalidConfigException $e) {
            Log::error('获取分类报错', $e);
        }
        try {
            $pages = $miniProgram->code->getPage();
        } catch (InvalidConfigException $e) {
            Log::error('获取页面配置报错', $e);
            return response('获取页面配置报错');
        }
        $status = $this->getLatestAuditStatus($request);
        $datas[] = $categorys;
        $datas[] = $pages;
        $datas[] = $status;
        return view('code/commit', compact('datas'));

    }

    /*获取最新一次审核状态 */
    function getLatestAuditStatus(Request $request)
    {
        $miniProgram = $this->getMiniProgram($request);
        try {
            $result = $miniProgram->code->getLatestAuditStatus();
            return $result;
        } catch (InvalidConfigException $e) {
            Log::error('获取最新一次审核状态出错', $e);
        }

        return 0;

    }

    function release(Request $request)
    {
        $miniProgram = $this->getMiniProgram($request);
        try {
            $result = $miniProgram->code->release();
        } catch (InvalidConfigException $e) {
            Log::error('发布小程序出错', $e);
            Log::error('发布小程序出错', $miniProgram);
        }
        if ($result) {
            return response('发布成功');
        }
        return response('发布失败');

    }

    function getQrCode(Request $request)
    {
        $miniProgram = $this->getMiniProgram($request);
        try {
            $result = $miniProgram->code->getQrCode();
        } catch (InvalidConfigException $e) {
            Log::error(' 小程序出错', $e);
            Log::error(' 小程序出错', $miniProgram);
        }
        if ($result) {
            return response($result);
        }
        return response('查询失败');

    }


    /**
     * @param Request $request
     * @return \EasyWeChat\OpenPlatform\Authorizer\MiniProgram\Application
     */
    public function getMiniProgram(Request $request): \EasyWeChat\OpenPlatform\Authorizer\MiniProgram\Application
    {
        $appid = $request->appid;
        $authorizer_refresh_token = $this->openPlatform->getAuthorizer($appid)['authorization_info']['authorizer_refresh_token'];
        $miniProgram = $this->openPlatform->miniProgram($appid, $authorizer_refresh_token);
        return $miniProgram;
    }
}