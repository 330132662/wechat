<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'Overtrue\LaravelWeChat\Events\OpenPlatform\Authorized' => ['App\Listeners\EventListener'],
        'Overtrue\LaravelWeChat\Events\OpenPlatform\UpdateAuthorized' => ['App\Listeners\UpdateAuthorizedListener'],
        'Overtrue\LaravelWeChat\Events\OpenPlatform\Unauthorized' => ['App\Listeners\UnauthorizedListener'],
        'Overtrue\LaravelWeChat\Events\OpenPlatform\VerifyTicketRefreshed' => ['App\Listeners\VerifyTicketRefreshedListener']
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
