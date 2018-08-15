<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 8:33
 */

namespace App\Http\Controllers\api;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

/**　商品列表 2018年6月20日08:33:24
 * Class ProductsController
 * @package App\Http\Controllers\api
 */
class ProductsController extends ApiController
{
    protected $accessTokenService;

    public function __construct(ProductService $accessTokenService)
    {
        $this->accessTokenService = $accessTokenService;
    }

    public function index1(Request $request)
    {
        $product = new Product();
        $products = $product->index($request);
        return response($products);
    }
/*这里不用自己写的 用信息化的api*/
    function index()
    {
        echo $this->getCorpId();
        return $this->accessTokenService->getContacts($this->getCorpId());
    }

    function show($id)
    {
        $pro = new Product();
        $result = $pro->show($id);
        if ($result) {
            return response($result);
        } else {
            response('暂无数据');
        }
    }

}