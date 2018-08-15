<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/15
 * Time: 18:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Article extends Model
{
//    protected $table='articles' ;
    public function store($req)
    {
        $this->name = $req['name'];
        $this->content = htmlspecialchars_decode($req['content']);
//        dd(Auth::user()->id);
        $this->weid = $req['weid'];
        $this->author = Auth::user()->id;
        $this->thumb = $req['thumb'];
        $this->type = $req['type'];

        return $this->save();
    }

}