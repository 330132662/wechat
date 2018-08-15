<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/21
 * Time: 20:10
 */

namespace App\Http\Controllers\api;


use App\Models\Weapp;
use WXBizMsgCrypt;

class WeappsController extends ApiController
{
    function show($id)
    {
        $app = Weapp::find($id);
        return response($app);
    }


    private $appid = 'wx2fd187ec0acc2876';            //第三方平台应用appid
    private $appsecret = '79a0e0c240fc806e61ddab3cb8b3b701';     //第三方平台应用appsecret
    private $token = 'xkho3qsixyd2xuhxu44csfrb0ne3axhp';           //第三方平台应用token（消息校验Token）
    private $encodingAesKey = 'Zu2u1CuZIBvV6OeqgN3hivdNfrYUNM2TkxoEF6SiG7K';      //第三方平台应用Key（消息加解密Key）
    private $ticket = "";
    private $component_ticket = "";   //微信后台推送的ticket,用于获取第三方平台接口调用凭据
    private $access_token = "";

    /*
    * 扫码授权，注意此URL必须放置在页面当中用户点击进行跳转，不能通过程序跳转，否则将出现“请确认授权入口页所在域名，与授权后回调页所在域名相同....”错误
    * @params string $redirect_uri : 扫码成功后的回调地址
    * @params int $auth_type : 授权类型，1公众号，2小程序，3公众号/小程序同时展现。不传参数默认都展示
    */
    public function startAuth($redirect_uri = 'https://taoshu.xinxihua.com', $auth_type = 3)
    {
        $url = "https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=" . $this->appid . "&pre_auth_code=" . $this->get_pre_auth_code() .
            "&redirect_uri=" . urlencode($redirect_uri) . "&auth_type=" . $auth_type;
        return $url;
    }

    /*
    * 获取第三方平台access_token
    * 注意，此值应保存，代码这里没保存
    */
    private function get_component_access_token()
    {

        $url = "https://api.weixin.qq.com/cgi-bin/component/api_component_token";
        $data = '{
            "component_appid":"' . $this->appid . '" ,
            "component_appsecret": "' . $this->appsecret . '",
            "component_verify_ticket": "' . $this->component_ticket . '"
        }';
        $ret = json_decode($this->https_post($url, $data));
        if ($ret->errcode == 0) {
            return $ret->component_access_token;
        } else {
            return $ret->errcode;
        }
    }

    /*
    *  第三方平台方获取预授权码pre_auth_code
    */
    private function get_pre_auth_code()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=" . $this->get_component_access_token();
        $data = '{"component_appid":"' . $this->appid . '"}';
        $ret = json_decode($this->https_post($url, $data));
        if ($ret->errcode == 0) {
            return $ret->pre_auth_code;
        } else {
            return $ret->errcode;
        }
    }

    /*
    * 发起POST网络提交
    * @params string $url : 网络地址
    * @params json $data ： 发送的json格式数据
    */
    private function https_post($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /*
   * 发起GET网络提交
   * @params string $url : 网络地址
   */
    private function https_get($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        if (curl_errno($curl)) {
            return 'Errno' . curl_error($curl);
        } else {
            $result = curl_exec($curl);
        }
        curl_close($curl);
        return $result;
    }

    function getTieket()
    {
//        $wxcrypt = new WXBizMsgCrypt('xkho3qsixyd2xuhxu44csfrb0ne3axhp', $this->encodingAesKey, $this->appid);
//        $wxcrypt->decryptMsg();
    }


}