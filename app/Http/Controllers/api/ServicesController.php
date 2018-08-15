<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 9:22
 */

namespace App\Http\Controllers\api;


use App\Models\Article;
use App\Models\Weapp;
use App\User;
use Illuminate\Http\Request;

class ServicesController extends ApiController
{
    public function index(Request $request)
    {
        $weid = $request->weid;
//      这里需要筛选哪个小程序的type为产品的列表    1=普通文章  2=服务 3=产品
        $articles = Article::where(['type' => 2, 'weid' => $weid])->OrderBy('id', 'desc')->paginate(10);
        if ($articles == null) {
            return response('没有数据', 405);
        }
        foreach ($articles as $article) {
            $article['author_name'] = User::getNick($article['author']);
            $article['weapp'] = Weapp::getName($article['weid']);
            $article['content'] = "";
        }
        return response($articles);

    }

}