<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26
 * Time: 16:42
 */

namespace App\Listeners;


use Illuminate\Support\Facades\Log;
use Overtrue\LaravelWeChat\Events\OpenPlatform\Unauthorized;

class UnauthorizedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
        Log::info('UnauthorizedListener   111');
    }

    /**
     * Handle the event. 未授权
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Unauthorized $event)
    {
        Log::info('payload', ['ss' => $event->payload]);
    }
}