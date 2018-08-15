<?php

namespace App\Providers;

use App\Foundation\Auth\XXHManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        // 启动信息化
       /* $this->app->singleton('xxh', function () {
            return new XXHManager($this->app);
        });*/
    }
}
