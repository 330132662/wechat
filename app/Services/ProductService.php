<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17
 * Time: 16:42
 */

namespace App\Services;


class ProductService
{

    protected $accessTokenService;

    function __construct(AccessTokenService $accessTokenService)
    {
        $this->accessTokenService = $accessTokenService;
    }

    public function getContacts($corpId)
    {

        $client = $this->accessTokenService->getIsvCorpClient($corpId);
        $response = $client->get('/products');
        \Log::info($response->getResponse());
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }
        return null;

    }
}