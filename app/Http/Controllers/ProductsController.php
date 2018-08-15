<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19
 * Time: 15:39
 */

namespace App\Http\Controllers;


use App\Foundation\Facades\XXH;
use App\Models\Product;
use App\Models\Weapp;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**产品/商品
 * Class ProductsController
 * @package App\Http\Controllers
 */
class ProductsController extends Controller
{

    protected $accessTokenService;

    public function __construct(ProductService $accessTokenService)
    {
        $this->accessTokenService = $accessTokenService;
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->id == 1) { //           为管理员展示所有商品
                $products = Product::paginate(10);
            } else {
                $weapps = Weapp::where(['uid' => Auth::user()->id])->get();
                $ids = array();
                foreach ($weapps as $app) {
                    $ids[] = $app->id;
                    $products = Product::whereIn('weid', $ids)->paginate(10);
                }

            }

            foreach ($products as $product) {
                $product->appname = Weapp::getName($product->weid);
            }
//            return $products;
        } else {
//            return false;
        }
        return view('product/index', compact('products'));
    }


    public function create()
    {
        $app = new Weapp();
        $apps = $app->index();
        $product = new Product();
        return view("product/create", compact("apps", 'product'));
    }

    public function store(Request $request)
    {
        $product = new Product();
        $isSave = $product->store($request);
        if ($isSave) {
            return response('操作成功');
        }
        return response('操作失败', 400);

    }

    function show($id)
    {
        $article = Product::find($id);
        $app = new Weapp();
        $apps = $app->index();
        return view('product/show', compact('article', 'apps'));
    }

    function edit($id)
    {
        $product = Product::find($id);
        $app = new Weapp();
        $apps = $app->index();
        return view('product/create', compact('product', 'apps'));
    }


}