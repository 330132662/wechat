<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/22
 * Time: 10:54
 */

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth', ['except' => ['show', 'create', 'store', 'index']]);
        $this->middleware('guest', ['only' => ['create']]);// 只让未登录用户访问注册页面
    }

    public function index()
    {
//        dd(bcrypt('secret'));

        if (!Auth::check() || Auth::id() != 10164) {
            return redirect('/');
        } else {
            $users = User::paginate(10);
            return view('users.index', compact('users'));
        }

    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(20);
        return view('users.show', compact('user', 'statuses'));
    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => bcrypt($request->password)]);
        Auth::login($user);// 自动登录

//        $this->sendEmailConfirmationTo($user);// 发送验证邮件

//        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect()->route('/', $user);
    }

    /** 加载修改页面
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        // 验证是否有权限访问
        if (!$this->authorize('update', $user)) {
            return view('users.edit', compact('user'));
        }
        return view('users.edit', compact('user'));
    }

    /**　执行更改资料的操作
     * @param Request $request
     * @param User $user
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, ['name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $this->authorize('update', $user);
        $data['name'] = $request->name;
        if ($request->password) { //当有密码传过来时才修改
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success', '个人资料更新成功');
        return redirect()->route('users.show', $user->id);


    }

    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册" . env("APP_NAME") . "！请确认你的邮箱。";/*  Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        })*/;

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = '粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }

    /*重置密码*/
    function resetpassword(Request $request)
    {
        if (empty($request->keys())) {// 加载页面
            return view('users/passreset');
        } else { //进行重置
            $pass = bcrypt($request->password);
            $uid = $request->uid;
            $user = User::find($uid);
            $user->password = $pass;
            $result = $user->update();
            if ($result) {
                return response('已经重置');
            }

            return response('重置失败', 201);
        }
    }


}