<?php

namespace App\Listeners;

use App\Events\Event;
use Illuminate\Support\Facades\Log;
use Overtrue\LaravelWeChat\Events\OpenPlatform\Authorized;

class EventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {   Log::info('EventListener   111');
        //
    }

    /**
     * Handle the event. 授权的
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Authorized $event)
    {

        Log::info('payload', ['ss' => $event->payload]);
    }
}
