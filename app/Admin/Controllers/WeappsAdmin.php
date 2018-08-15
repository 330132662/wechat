<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 14:54
 */

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Weapp;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WeappsAdmin extends Controller
{
    function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('小程序管理');
            $content->description('11');
            $content->body($this->grid());
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Edit');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    function show($weappid)
    {

    }

    protected function grid()
    {
        return Admin::grid(Weapp::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->apptitle();
            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Weapp::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}