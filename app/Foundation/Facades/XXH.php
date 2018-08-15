<?php
/**
 * Created by PhpStorm.
 * User: HTMC
 * Date: 2017/3/31
 * Time: 15:02
 */

namespace App\Foundation\Facades;


use Illuminate\Support\Facades\Facade;

class XXH extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'xxh';
    }
}