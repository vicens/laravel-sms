<?php

namespace Vicens\LaravelSms;

use Vicens\LaravelSms\Drivers\Aliyun;
use Vicens\LaravelSms\Drivers\ChuangLan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Manager as BaseManager;

/**
 * Class Manager
 *
 * @package Packages\LaravelSms
 * @method bool send(string $mobile, Contracts\Messages\Message $message);
 */
class Manager extends BaseManager
{

    /**
     * 默认驱动
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return Config::get('sms.default');
    }

    /**
     * 阿里大于驱动(新版本)
     *
     * @return Aliyun
     */
    protected function createAliyunDriver()
    {
        return new Aliyun(Config::get('sms.aliyun', []));
    }

    /**
     * 创蓝253
     *
     * @return ChuangLan
     */
    protected function createChuangLanDriver()
    {
        return new ChuangLan(Config::get('sms.chuanglan'));
    }
}
