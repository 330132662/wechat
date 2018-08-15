<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Overtrue\LaravelWeChat\Events\OpenPlatform\UpdateAuthorized;

class UpdateAuthorizedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info('UnauthorizedListener   111');
    }

    /**
     * Handle the event.  更新授权的
     *
     * @param  Event $event
     * @return void
     */
    public function handle(UpdateAuthorized $event)
    {
        Log::info('payload', ['ss' => $event->payload]);
    }
}