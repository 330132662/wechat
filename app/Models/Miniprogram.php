<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 15:03
 */

namespace App\Models;


/*
*    代小程序实现业务
*/

namespace app\home\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Miniprogram extends Model
{
    private $thirdAppId;        //开放平台appid
    private $encodingAesKey;    //开放平台encodingAesKey
    private $thirdToken;        //开放平台token
    private $thirdAccessToken;  //开放平台access_token

    private $authorizer_appid;
    private $authorizer_access_token;
    private $authorizer_refresh_token;

    public function __construct($appid)
    {
        $weixin_account = Db::name('weixin_account')->where(['type' => 1])->field('token,encodingAesKey,appId,component_access_token')->find();
        if ($weixin_account) {
            $this->thirdAppId = $weixin_account['appId'];
            $this->encodingAesKey = $weixin_account['encodingAesKey'];
            $this->thirdToken = $weixin_account['token'];
            $this->thirdAccessToken = $weixin_account['component_access_token'];

            $miniprogram = Db::name('wxminiprograms')->where('authorizer_appid', $appid)
                ->field('authorizer_access_token,authorizer_refresh_token,authorizer_expires')->find();
            if ($miniprogram) {
                $this->authorizer_appid = $appid;
                if (time() > $miniprogram['authorizer_expires']) {
                    $miniapp = $this->update_authorizer_access_token($appid, $miniprogram['authorizer_refresh_token']);
                    if ($miniapp) {
                        $this->authorizer_access_token = $miniapp->authorizer_access_token;
                        $this->authorizer_refresh_token = $miniapp->authorizer_refresh_token;
                    } else {
                        $this->errorLog("更新小程序access_token失败,appid:" . $this->authorizer_appid, '');
                        exit;
                    }
                } else {
                    $this->authorizer_access_token = $miniprogram['authorizer_access_token'];
                    $this->authorizer_refresh_token = $miniprogram['authorizer_refresh_token'];
                }

            } else {
                $this->errorLog("小程序不存在,appid:" . $this->authorizer_appid, '');
                exit;
            }
        } else {
            $this->errorLog("请增加微信第三方公众号平台账户信息", '');
            exit;
        }
    }

    /*
     * 设置小程序服务器地址，无需加https前缀，但域名必须可以通过https访问
     * @params string / array $domains : 域名地址。只接收一维数组。
     * */
    public function setServerDomain($domain = 'test.moh.cc')
    {
        $url = "https://api.weixin.qq.com/wxa/modify_domain?access_token=" . $this->authorizer_access_token;
        if (is_array($domain)) {
            $https = '';
            $wss = '';
            foreach ($domain as $key => $value) {
                $https .= '"https://' . $value . '",';
                $wss .= '"wss://' . $value . '",';
            }
            $https = rtrim($https, ',');
            $wss = rtrim($wss, ',');
            $data = '{
                "action":"add",
                "requestdomain":[' . $https . '],
                "wsrequestdomain":[' . $wss . '],
                "uploaddomain":[' . $https . '],
                "downloaddomain":[' . $https . ']
            }';
        } else {
            $data = '{
                "action":"add",
                "requestdomain":"https://' . $domain . '",
                "wsrequestdomain":"wss://' . $domain . '",
                "uploaddomain":"https://' . $domain . '",
                "downloaddomain":"https://' . $domain . '"
            }';
        }
        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("设置小程序服务器地址失败,appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 设置小程序业务域名，无需加https前缀，但域名必须可以通过https访问
     * @params string / array $domains : 域名地址。只接收一维数组。
     * */
    public function setBusinessDomain($domain = 'test.moh.cc')
    {
        $url = "https://api.weixin.qq.com/wxa/setwebviewdomain?access_token=" . $this->authorizer_access_token;
        if (is_array($domain)) {
            $https = '';
            foreach ($domain as $key => $value) {
                $https .= '"https://' . $value . '",';
            }
            $https = rtrim($https, ',');
            $data = '{
                "action":"add",
                "webviewdomain":[' . $https . ']
            }';
        } else {
            $data = '{
                "action":"add",
                "webviewdomain":"https://' . $domain . '"
            }';
        }

        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("设置小程序业务域名失败,appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 成员管理，绑定小程序体验者
     * @params string $wechatid : 体验者的微信号
     * */
    public function bindMember($wechatid)
    {
        $url = "https://api.weixin.qq.com/wxa/bind_tester?access_token=" . $this->authorizer_access_token;
        $data = '{"wechatid":"' . $wechatid . '"}';
        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("绑定小程序体验者操作失败,appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 成员管理，解绑定小程序体验者
     * @params string $wechatid : 体验者的微信号
     * */
    public function unBindMember($wechatid)
    {
        $url = "https://api.weixin.qq.com/wxa/unbind_tester?access_token=" . $this->authorizer_access_token;
        $data = '{"wechatid":"' . $wechatid . '"}';
        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("解绑定小程序体验者操作失败,appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
    * 成员管理，获取小程序体验者列表
    * */
    public function listMember()
    {
        $url = "https://api.weixin.qq.com/wxa/memberauth?access_token=" . $this->authorizer_access_token;
        $data = '{"action":"get_experiencer"}';
        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            return $ret->members;
        } else {
            $this->errorLog("获取小程序体验者列表操作失败,appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 为授权的小程序帐号上传小程序代码
     * @params int $template_id : 模板ID
     * @params json $ext_json : 小程序配置文件，json格式
     * @params string $user_version : 代码版本号
     * @params string $user_desc : 代码描述
     * */
    public function uploadCode($template_id = 1, $user_version = 'v1.0.0', $user_desc = "魔盒CMS小程序模板库")
    {
        $ext_json = json_encode('{"extEnable": true,"extAppid": "wx572****bfb","ext":{"appid": "' . $this->authorizer_appid . '"}}');
        $url = "https://api.weixin.qq.com/wxa/commit?access_token=" . $this->authorizer_access_token;
        $data = '{"template_id":"' . $template_id . '","ext_json":' . $ext_json . ',"user_version":"' . $user_version . '","user_desc":"' . $user_desc . '"}';
        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("为授权的小程序帐号上传小程序代码操作失败,appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 获取体验小程序的体验二维码
     * @params string $path :   指定体验版二维码跳转到某个具体页面
     * */
    public function getExpVersion($path = '')
    {
        if ($path) {
            $url = "https://api.weixin.qq.com/wxa/get_qrcode?access_token=" . $this->authorizer_access_token . "&path=" . urlencode($path);
        } else {
            $url = "https://api.weixin.qq.com/wxa/get_qrcode?access_token=" . $this->authorizer_access_token;
        }
        $ret = json_decode(https_get($url));
        if ($ret->errcode) {
            $this->errorLog("获取体验小程序的体验二维码操作失败,appid:" . $this->authorizer_appid, $ret);
            return false;
        } else {
            return $url;
        }
    }

    /*
     * 提交审核
     * @params string $tag : 小程序标签，多个标签以空格分开
     * @params strint $title : 小程序页面标题，长度不超过32
     * */
    public function submitReview($tag = "魔盒CMS 微信投票 微网站 微信商城", $title = "魔盒CMS微信公众号营销小程序开发")
    {
        $first_class = '';
        $second_class = '';
        $first_id = 0;
        $second_id = 0;
        $address = "pages/index/index";
        $category = $this->getCategory();
        if (!empty($category)) {
            $first_class = $category[0]->first_class ? $category[0]->first_class : '';
            $second_class = $category[0]->second_class ? $category[0]->second_class : '';
            $first_id = $category[0]->first_id ? $category[0]->first_id : 0;
            $second_id = $category[0]->second_id ? $category[0]->second_id : 0;
        }
        $getpage = $this->getPage();
        if (!empty($getpage) && isset($getpage[0])) {
            $address = $getpage[0];
        }
        $url = "https://api.weixin.qq.com/wxa/submit_audit?access_token=" . $this->authorizer_access_token;
        $data = '{
                "item_list":[{
                    "address":"' . $address . '",
                    "tag":"' . $tag . '",
                    "title":"' . $title . '",
                    "first_class":"' . $first_class . '",
                    "second_class":"' . $second_class . '",
                    "first_id":"' . $first_id . '",
                    "second_id":"' . $second_id . '"
                }]
            }';
        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            Db::name('wxminiprogram_audit')->insert([
                'appid' => $this->authorizer_appid,
                'auditid' => $ret->auditid,
                'create_time' => date('Y-m-d H:i:s')
            ]);
            return true;
        } else {
            $this->errorLog("小程序提交审核操作失败，appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 小程序审核撤回
     * 单个帐号每天审核撤回次数最多不超过1次，一个月不超过10次。
     * */
    public function unDoCodeAudit()
    {
        $url = "https://api.weixin.qq.com/wxa/undocodeaudit?access_token=" . $this->authorizer_access_token;
        $ret = json_decode(https_get($url));
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("小程序审核撤回操作失败，appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 查询指定版本的审核状态
     * @params string $auditid : 提交审核时获得的审核id
     * */
    public function getAuditStatus($auditid)
    {
        $url = "https://api.weixin.qq.com/wxa/get_auditstatus?access_token=" . $this->authorizer_access_token;
        $data = '{"auditid":"' . $auditid . '"}';
        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            $reason = $ret->reason ? $ret->reason : '';
            Db::name('wxminiprogram_audit')->where(['appid' => $this->authorizer_appid, 'auditid' => $auditid])->update([
                'status' => $ret->status,
                'reason' => $reason
            ]);
            return true;
        } else {
            $this->errorLog("查询指定版本的审核状态操作失败，appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 查询最新一次提交的审核状态
     * */
    public function getLastAudit()
    {
        $url = "https://api.weixin.qq.com/wxa/get_latest_auditstatus?access_token=" . $this->authorizer_access_token;
        $ret = json_decode(https_get($url));
        if ($ret->errcode == 0) {
            $reason = $ret->reason ? $ret->reason : '';
            Db::name('wxminiprogram_audit')->where(['appid' => $this->authorizer_appid, 'auditid' => $ret->auditid])->update([
                'status' => $ret->status,
                'reason' => $reason
            ]);
            return $ret->auditid;
        } else {
            $this->errorLog("查询最新一次提交的审核状态操作失败，appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 发布已通过审核的小程序
     * */
    public function release()
    {
        $url = "https://api.weixin.qq.com/wxa/release?access_token=" . $this->authorizer_access_token;
        $data = '{}';
        $ret = json_decode(https_post($url, $data));
        if ($ret->errcode == 0) {
            return true;
        } else {
            $this->errorLog("发布已通过审核的小程序操作失败，appid:" . $this->authorizer_appid, $ret);
            return $ret->errcode;
        }
    }

    /*
     * 获取授权小程序帐号的可选类目
     * */
    private function getCategory()
    {
        $url = "https://api.weixin.qq.com/wxa/get_category?access_token=" . $this->authorizer_access_token;
        $ret = json_decode(https_get($url));
        if ($ret->errcode == 0) {
            return $ret->category_list;
        } else {
            $this->errorLog("获取授权小程序帐号的可选类目操作失败，appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
     * 获取小程序的第三方提交代码的页面配置
     * */
    private function getPage()
    {
        $url = "https://api.weixin.qq.com/wxa/get_page?access_token=" . $this->authorizer_access_token;
        $ret = json_decode(https_get($url));
        if ($ret->errcode == 0) {
            return $ret->page_list;
        } else {
            $this->errorLog("获取小程序的第三方提交代码的页面配置失败，appid:" . $this->authorizer_appid, $ret);
            return false;
        }
    }

    /*
    * 更新授权小程序的authorizer_access_token
    * @params string $appid : 小程序appid
    * @params string $refresh_token : 小程序authorizer_refresh_token
    * */
    private function update_authorizer_access_token($appid, $refresh_token)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=' . $this->thirdAccessToken;
        $data = '{"component_appid":"' . $this->thirdAppId . '","authorizer_appid":"' . $appid . '","authorizer_refresh_token":"' . $refresh_token . '"}';
        $ret = json_decode(https_post($url, $data));
        if (isset($ret->authorizer_access_token)) {
            Db::name('wxminiprograms')->where(['authorizer_appid' => $appid])->update(['authorizer_access_token' => $ret->authorizer_access_token, 'authorizer_expires' => (time() + 7200), 'authorizer_refresh_token' => $ret->authorizer_refresh_token]);
            return $ret;
        } else {
            $this->errorLog("更新授权小程序的authorizer_access_token操作失败,appid:" . $appid, $ret);
            return null;
        }
    }

    private function errorLog($msg, $ret)
    {
        file_put_contents(ROOT_PATH . 'runtime/error/miniprogram.log', "[" . date('Y-m-d H:i:s') . "] " . $msg . "," . json_encode($ret) . PHP_EOL, FILE_APPEND);
    }
}