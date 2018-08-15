<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/13
 * Time: 15:57
 */

namespace App\Http\Controllers;

/**　 平台首页
 * Class IndexController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    function index()
    {

        return view('index');
    }
}