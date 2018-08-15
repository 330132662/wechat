<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/14
 * Time: 11:13
 */

namespace App\Http\Controllers;


use App\Models\Weapp;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class WeappsController extends Controller
{
    protected $openPlatform;
    protected $app;
    protected $config;

    public function __construct()
    {
        $this->config = [
            'app_id' => env("WECHAT_OPEN_PLATFORM_APPID"),
            'secret' => env("WECHAT_OPEN_PLATFORM_SECRET"),
            'token' => env("WECHAT_OPEN_PLATFORM_TOKEN"),
            'aes_key' => env("WECHAT_OPEN_PLATFORM_AES_KEY")
        ];
//        $this->openPlatform = Factory::openPlatform($this->config);
        $this->openPlatform = app('wechat.open_platform.default');//超哥推荐
    }

    /**
     * 创建一个小程序
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $app = new Weapp();
        $isSave = $app->store($request);
        if ($isSave) {
            return response("成功创建小程序", 200);
        }
        return response("创建失败", 401);
    }

    public function index()
    {
        $app = new Weapp();
        $apps = $app->index();
        return view("index", compact("apps"));
    }

    public function edit($id)
    {
//        获取小程序目前内容
        $app = Weapp::find($id);
        Session::put('weapp_auth', $id);
        if ($app->appid != '') { // 已经绑定微信小程序
           $authorizer = $this->openPlatform->getAuthorizer($app->appid)['authorizer_info'];
        } else {
            $authorizer = [];
        }


        return view('weapp/edit', compact('app', 'authorizer'));
    }

    public function update(Request $request, $id)
    {
        $apptitle = $request->apptitle;
        $homevideo = $request->homevideo;
        $navtitle = json_encode($request->nav);//implode(',', );
        $company = $request->company;
        $addr = $request->addr;
        $tel = $request->tel;
        $introduce = $request->introduce;


        $app = Weapp::find($id);
        $app->apptitle = $apptitle;
        $app->nav = $navtitle;
        $app->homevideo = $homevideo;
        $app->company = $company;
        $app->addr = $addr;
        $app->tel = $tel;
        $app->introduce = $introduce;
        $result = $app->update();
        if ($result) {
            return redirect()->route('weapps.edit', $id);
        }
        return redirect()->back();


    }


}