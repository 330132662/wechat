<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/14
 * Time: 11:14
 */

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Weapp extends Model
{
    protected $table = "weapps";
    protected $fillable = ['name', 'status', 'img', 'uid'];

    public function store($req)
    {
        $this->name = $req['name'];
        $uid = Auth::id();
//        dd(Auth::user());
        $this->uid = $uid;
        $this->status = 1;
        $this->img = $req['img'];

        return $this->save();

    }

    public function user()
    {
        return $this->belongsTo(User::class);// 一个小程序 属于一个用户
    }

    public function index()
    {
        if (Auth::check()) { // 确认登录状态
            if (Auth::user()->user_id == 10164) {
                $apps = Weapp::paginate(10);
            } else {
                $apps = Weapp::where(['uid' => Auth::id()])->paginate(10);
            }

        } else {
            $apps = [];
        }


//        $apps = Weapp::paginate(10); //TODO 测试用  无需登录查看所有小程序，线上严禁打开
        return $apps;
    }

    static function getName($weid)
    {
        if (!empty($weid) && $weid > 0) {
            return Weapp::find($weid)['apptitle'];
        } else {
            return "未获取到";
        }

    }
}