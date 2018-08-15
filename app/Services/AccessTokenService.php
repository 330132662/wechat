<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2017-11-18
 * Time: 9:05
 */

namespace App\Services;


use App\Foundation\Facades\XXH;
use App\Models\Corporation;
use App\Models\CorporationPermanentCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Someline\Rest\RestClient;

class AccessTokenService
{

    protected $corporation;

    /**
     * @var CorporationPermanentCode
     */
    protected $permanentCode;

    /**
     * @var RestClient
     */
    protected $corp_client;

    /**
     * @var RestClient
     */
    protected $isv_client;

    protected $oauth_tokens = [];

    protected $oauth_tokens_cache_key = 'xxh-rest-corp-client.oauth_tokens';

    protected $service_name;

    protected $use_cache_token = null;

    function __construct(Corporation $corporation, CorporationPermanentCode $permanentCode)
    {
        $this->permanentCode = $permanentCode;
        $this->corporation = $corporation;
        $this->setUp();

    }

    protected function setUp()
    {
        $this->service_name = 'api';
        $minutes = $this->getConfig('oauth_tokens_cache_minutes', 10);
        $this->use_cache_token = $minutes > 0;
        $this->useOAuthTokenFromCache();
    }

    public function getIsvClient()
    {
        $this->isv_client = new RestClient($this->service_name, false);
        $this->isv_client->withOAuthTokenTypeClientCredentials();
        return $this->isv_client;
    }

    public function getIsvCorpClient($authCorpId = null)
    {
        if (!$authCorpId) {
            $authCorpId = XXH::id();
        }
        $this->corp_client = new RestClient($this->service_name, false);
        $access_token = $this->getIsvCorpAccessToken($authCorpId);
        $this->corp_client->setOAuthToken($authCorpId, $access_token);
        $this->corp_client->withOAuthToken($authCorpId);
        return $this->corp_client;
    }

    //##################获取access_token##################

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getConfig($key, $default = null)
    {
        return config("rest-client.$key", $default);
    }


    /**
     * 读取缓存
     */
    public function useOAuthTokenFromCache()
    {
        if (!$this->use_cache_token) {
            return;
        }

        $this->oauth_tokens = \Cache::get($this->getOauthTokensCacheKey(), []);

    }


    /**
     * @param $authCorpId
     * @return mixed
     */
    public function getIsvCorpAccessToken($authCorpId)
    {
        if (!isset($this->oauth_tokens[$authCorpId])) {
            // request access token
            $corporation = $this->corporation->newQuery()->find($authCorpId);
            /*  if ($corporation == null || $corporation == '') {
                  return response("corporation为空", 201);
              }*/
            $response = $this->postRequestAccessToken($this->getOAuthGrantRequestData($authCorpId, $corporation->permanentCode->permanent_code));
            Log::info($response->getResponse());
            // handle access token
            if ($response->getResponse()->getStatusCode() != 200) {
                throw new \RuntimeException('Failed to get access token for corporation [' . $authCorpId . ']!');
            }

            $result = $response->getResponseData();

            if (!isset($result['data']['access_token'])) {
                throw new \RuntimeException('"access_token" is not exists in the response data!');
            }
            $access_token = $result['data']['access_token'];
            $this->setOAuthToken($authCorpId, $access_token);
        }
        return $this->oauth_tokens[$authCorpId];
    }


    /**
     * @param $data
     * @return mixed
     */
    public function postRequestAccessToken($data)
    {
        return $this->getIsvClient()->post(config('auth.agent.corp_token_api'), $data);
    }

    /**
     * @param $authCorpId
     * @param $permanentCode
     * @return array
     */
    public function getOAuthGrantRequestData($authCorpId, $permanentCode)
    {
        return [
            'corp_id' => $authCorpId,
            'permanent_code' => $permanentCode
        ];
    }


    /**
     * @param $authCorpId
     * @param $access_token
     */
    public function setOAuthToken($authCorpId, $access_token)
    {
        if (empty($access_token)) {
            unset($this->oauth_tokens[$authCorpId]);
        } else {
            $this->oauth_tokens[$authCorpId] = $access_token;
        }

        // update to cache
        $minutes = $this->getConfig('oauth_tokens_cache_minutes', 100);
        \Cache::put($this->getOauthTokensCacheKey(), $this->oauth_tokens, $minutes);
    }


    /**
     * 获取 缓存 键
     *
     * @return string
     */
    protected function getOauthTokensCacheKey()
    {
        return $this->oauth_tokens_cache_key . '.' . $this->service_name;
    }

    /**
     * 第一步：读取 永久授权码
     */
    function getPermanentCode()
    {
        $corp_id = \XXH::id();
        $permanent = DB::table('corporation_permanent_codes')->where(['corp_id' => $corp_id])->get();
        return $permanent['permanent_code'];


    }


}