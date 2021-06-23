<?php


namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class KlaviyoConnection extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'App\Services\KlaviyoConnection';
    }

}
