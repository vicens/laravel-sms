<?php

namespace Vicens\LaravelSms\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'sms.manager';
    }
}