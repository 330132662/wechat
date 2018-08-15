<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\CorporationRepository;
use App\Repositories\UserRepository;
use App\Services\IsvService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * @var IsvService
     */
    protected $isv;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * LoginController constructor.
     * @param IsvService $isv
     * @param UserRepository $user
     * @param CorporationRepository $corporation
     */
    /*  public function __construct(IsvService $isv, UserRepository $user, CorporationRepository $corporation)
      {

          // parent::__construct();
  //        $this->middleware('guest')->except('logout');
          $this->gatewayUrl = config('auth.agent.gateway_url');
          $this->agentId = config('auth.agent.agent_id');
          $this->isv = $isv;
          $this->user = $user;
          $this->corporation = $corporation;


      }*/

    public function __construct()
    {

//        parent::__construct();
        $this->middleware('guest')->except('logout');


    }

    public function callback(Request $request)
    {
        $auth_code = $request->get('auth_code');
        $corp_id = $request->get('corp_id');
        if (!$auth_code || !$corp_id) {
            return '参数错误！';
        }
        try {
            $user = $this->isv->setUserInfo($corp_id, $auth_code);
            if (!$user) {
                return '获取用户信息失败';
            }
            $corporation = $this->isv->setAuthInfo($corp_id);
            if (!$corporation) {
                return '获取企业信息失败';
            }

            // 执行登录
            $this->guard()->login($user, false);
            \XXH::entryCorp($corporation);
            return $this->sendLoginResponse($request);


        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
            \Log::info($exception->getTraceAsString());
        }
        return '登陆失败或授权码已过期';
    }
}
