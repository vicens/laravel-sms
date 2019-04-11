<?php

namespace Vicens\LaravelSms\Contracts\Drivers;

use Vicens\LaravelSms\Contracts\Messages\ContentMessage;

interface ContentMessageDriver extends Driver
{
    /**
     * 发送内容消息
     *
     * @param string $mobile
     * @param ContentMessage $message
     * @return bool
     */
    public function send($mobile, ContentMessage $message);
}