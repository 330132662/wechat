<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/14
 * Time: 10:15
 */

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

/**模板控制器
 * Class TemplatesController
 * @package App\Http\Controllers
 */
class TemplatesController extends Controller
{
    function index()
    {
        $template = new Template();
        $templates = $template->index();
        return view('weapp/templates', compact("templates"));
    }

    public function edit($tpl_id)
    {
        if (empty($tpl_id)) { //新建
            return view("weapp/templates_edit");
        } else {

        }

    }

    public function store(Request $request)
    {
        $tpl = new Template();
        $isSave = $tpl->store($request);

        if ($isSave) {
            return redirect('templates')->with("success", "添加新模板");
        }
        return redirect()->back()->with("添加失败");//response("添加失败", 400);

    }

}