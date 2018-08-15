<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/15
 * Time: 14:57
 */

namespace App\Http\Controllers;


use App\Models\Article;
use App\Models\Weapp;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    function index()
    {
        $articles = Article::OrderBy('id', 'desc')->paginate(10); //where(['type' => 1])->
        foreach ($articles as $article) {
            $article['author_name'] = User::getNick($article['author']);
            $article['weapp'] = Weapp::getName($article['weid']);
        }

        return view('weapp/articles', compact("articles"));
    }

    function create()
    {
//     加载已有的小程序
        $app = new Weapp();
        $apps = $app->index();
        $users = User::all();
        return view('weapp/articles_create', compact('apps', 'users'));
//        return view('weapp/editor');
    }

    function store(Request $request)
    {
        if (Auth::check()) {
            $article = new Article();
            $isSave = $article->store($request);
        } else {
            return response('请登录！', 403);
        }
        if ($isSave) {
            return response('发布成功！');
        }
        return response('发布失败！！', 401);
    }

    public function show($aid)
    {
        $article = Article::find($aid);
        return view('weapp/articles_show', compact('article'));
    }

}