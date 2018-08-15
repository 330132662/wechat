<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26
 * Time: 16:42
 */

namespace App\Listeners;


use Illuminate\Support\Facades\Log;
use Overtrue\LaravelWeChat\Events\OpenPlatform\VerifyTicketRefreshed;

class VerifyTicketRefreshedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
		 Log::info('VerifyTicketRefreshed __construct');
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event $event ticket刷新的
     * @return void
     */
    public function handle(VerifyTicketRefreshed $event)
    {
        Log::info('VerifyTicketRefreshed', $event->payload);
    }
}