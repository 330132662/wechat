<?php
/**
 * Created by PhpStorm.
 * User: HTMC
 * Date: 2017/6/9
 * Time: 18:26
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Services\IsvService;
use App\Support\Crypto\XxhCrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServeController extends Controller
{

    protected $isv;

    protected $token;
    protected $encoding_key;
    protected $suite_key;

    public function __construct(IsvService $isv)
    {
        //parent::__construct();
        Log::info('--ServeController__construct--');
        $this->isv = $isv;
        $this->token = config('auth.agent.token');
        $this->encoding_key = config('auth.agent.encoding_key');
        $this->suite_key = config('rest-client.shared_service_config.oauth2_credentials.client_id');

    }


    public function serve(Request $request)
    {


        Log::info('--serve--');
        $signature = $request->get("signature");
        $timeStamp = $request->get("timestamp");
        $nonce = $request->get("nonce");

        $data = $request->all();
        $encrypt = $data['encrypt'];
        $crypt = new XxhCrypt($this->token, $this->encoding_key, $this->suite_key);

        $msg = "";
        $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
        if ($errCode != 0) {
            return 'error';
        } else {

            try {
                $eventMsg = json_decode($msg);
                $eventType = $eventMsg->EventType;

                $code = 'error';
                switch ($eventType) {
                    case 'permanent_code':

                        $code = $this->install($eventMsg);
                        break;
                    case 'del_permanent_code':

                        $code = $this->uninstall($eventMsg);
                        break;

                }
                return $code;
            } catch (\Exception $exception) {
                return 'error';
            }
        }
    }


    /**
     * 安装
     *
     * @param $eventMsg
     * @return string
     *
     */
    private function install($eventMsg)
    {

        return $this->isv->install($eventMsg);

    }

    /**
     * 卸载
     *
     * @param $eventMsg
     * @return string
     *
     */
    private function uninstall($eventMsg)
    {

        return $this->isv->uninstall($eventMsg);
    }

}