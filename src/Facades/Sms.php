<?php

namespace Vicens\LaravelSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Sms
 *
 * @method static bool send(string $mobile, \Vicens\LaravelSms\Contracts\Messages\Message $message)
 * @package Vicens\LaravelSms\Facades
 */
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
