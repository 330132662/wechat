<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2017-11-18
 * Time: 8:54
 */

namespace App\Services;


use App\Models\Corporation;
use App\Models\Contact;
use App\Models\CorporationPermanentCode;
use App\User;

class IsvService
{


    /**
     * @var AccessTokenService
     */
    protected $accessTokenService;
    /**
     * @var Corporation
     */
    protected $corporation;
    /**
     * @var CorporationPermanentCode
     */
    protected $permanentCode;
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Contact
     */
    protected $contact;

    function __construct(AccessTokenService $accessTokenService, Corporation $corporation, CorporationPermanentCode $permanentCode, User $user, Contact $contact)
    {
        $this->accessTokenService = $accessTokenService;
        $this->corporation = $corporation;
        $this->permanentCode = $permanentCode;
        $this->user = $user;
        $this->contact = $contact;
    }


    public function install($eventMsg)
    {


        $corp_id = $eventMsg->CorpId;
        $corp_name = $eventMsg->CorpName;
        $permanent_code = $eventMsg->PermanentCode;

        \Log::info('安装信息：', [
            $corp_id,
            $permanent_code
        ]);

        //
        $authInfo = $this->permanentCode->newQuery()->find($corp_id);

        if ($authInfo) {

            $authInfo->permanent_code = $permanent_code;
            $authInfo->saveOrFail();

            return 'success';
        }


        $arr['permanent_code'] = $permanent_code;

        $arr['corp_id'] = $corp_id;
        $arr['name'] = $corp_name;

        if ($this->permanentCode->forceFill($arr)->save()) {

            // 添加一条记录到 corporations表
            $this->corporation->newQuery()->updateOrCreate([
                'corp_id' => $corp_id
            ],
                [
                    'name' => $corp_name,
                    'status' => 1
                ]);
            return 'success';

        }
        return 'false';

    }

    public function uninstall($eventMsg)
    {

        // 这里最好是 软删除

        try {

            $this->corporation->newQuery()->where('corp_id', $eventMsg->CorpId)->delete();
            $this->permanentCode->newQuery()->where('corp_id', $eventMsg->CorpId)->delete();
        } catch (\Exception $exception) {

            \Log::info($exception->getTraceAsString());
            return 'false';
        }

        return 'success';

    }


    /**
     * @param $authCorpId
     * @return Corporation|null
     */
    public function setAuthInfo($authCorpId)
    {
        $isvClient = $this->accessTokenService->getIsvClient();

        // 获取永久授权码

        $permanent_code = $this->permanentCode->newQuery()->find($authCorpId);
        if ($permanent_code) {
            $response = $isvClient->get(config('auth.agent.corp_info'), [
                'corp_id' => $authCorpId,
                'permanent_code' => $permanent_code->permanent_code
            ]);

            \Log::info($response->getResponse());
            if ($response->isResponseSuccess()) {

                $result = $response->getResponseData();

                if (isset($result['data'])) {
                    return $this->corporation->updateOrCreate([
                        'corp_id' => $result['data']['corp_id']
                    ], $result['data']);
                }

            }
        }


        return null;
    }


    /**
     * @return User|null
     */
    public function setUserInfo()
    {
        $contact = [];
        $user = [];
        // 获取参数数量
        $args = func_get_args();

        $num_args = func_num_args();
        if ($num_args == 2) {
            $authCorpId = $args[0];
            $authCode = $args[1];


            $isvCorpClient = $this->accessTokenService->getIsvCorpClient($authCorpId);

            $response = $isvCorpClient->get(config('auth.agent.corp_user_api'),
                [
                    'auth_code' => $authCode
                ]
            );

            \Log::info($response->getResponse());
            if ($response->isResponseSuccess()) {

                $result = $response->getResponseData();

                // 获取用户信息
                if (isset($result['data'])) {
                    $contact = $result['data'];
                    $user = $result['data']['user'];
                }
            }

        } else if ($num_args == 1) {
//          这里没走
            $result = $args[0];
            $contact = $result;
            $user = $result['user'];

        }
        if (!empty($user) && !empty($contact)) {
            $this->contact->updateOrCreate([
                'contact_id' => $contact['contact_id']
            ], $contact);

            return $this->user->updateOrCreate([
                'user_id' => $user['user_id']
            ], $user);
            /* return $this->user->updateOrCreate([
                 'corp_id' => $result['corp_id']
             ], $user);*/
        }

        return null;

    }


    /**
     * @param $authCorpId
     * @param $authCode
     * @return IsvService|null
     */
    public function getUserInfo($authCorpId, $authCode)
    {


        $isvCorpClient = $this->accessTokenService->getIsvCorpClient($authCorpId);

        $response = $isvCorpClient->get(config('auth.agent.corp_user_api'),
            [
                'auth_code' => $authCode
            ]
        );
        \Log::info($response->getResponse());

        if ($response->isResponseSuccess()) {

            $result = $response->getResponseData();
            if (isset($result['data'])) {
                return $result['data'];
            }
        }
        return null;

    }
}