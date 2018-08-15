<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/14
 * Time: 10:18
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = "templates";

    function index()
    {
        $aa = Template::paginate(10);
//        dd($aa);
        return $aa;
    }

    function store($req)
    {
        $this->name = $req['name'];
        $this->img = $req['img'];
        return $this->save();

    }
}