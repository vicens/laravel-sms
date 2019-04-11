<?php

namespace Vicens\LaravelSms\Contracts\Drivers;

use Vicens\LaravelSms\Contracts\Messages\TemplateMessage;

interface TemplateMessageDriver extends Driver
{
    /**
     * 发送模板消息
     *
     * @param string $mobile
     * @param TemplateMessage $message
     * @return bool
     */
    public function send($mobile, TemplateMessage $message);
}