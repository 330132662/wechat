<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/15
 * Time: 18:59
 */

namespace App\Http\Controllers\api;


use App\Models\Article;
use Illuminate\Http\Request;

class ArticlesController extends ApiController
{
    public function index(Request $request)
    {
        $weid = $request->weid;
        $type = $request->type;

        if ($weid == 0) {
            $articles = Article::orderBy('updated_at', 'desc')->paginate(5);
        } else {
            $conditions = [];
            if (!empty($type)) {
                $conditions["type"] = $type;
            }
            $conditions['weid'] = $weid;
            $articles = Article::where($conditions)->orderBy('updated_at', 'desc')->paginate(5);//where(["weid" => $weid])->
        }
//        获取该用户所有文章
        return response($articles);

    }

    public function show($aid)
    {
        $article = Article::find($aid);
//        return response(env("APP_URL") . '/products/' . $aid);
//        return view('weapp/articles_show', compact('article'));
        return response($article);
    }

    function index1()
    {
//        $this->send_post('http://1d63389r57.iask.in/api/articles?page=1&limit=20&keyword=&sort=', '');
    }

    function send_post($url, $post_data)
    {


        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

}