<?php

namespace Vicens\LaravelSms\Contracts\Notifications;

use Vicens\LaravelSms\Contracts\Messages\{
    Message,
    TemplateMessage,
    ContentMessage
};

interface SmsNotification
{
    /**
     * 返回消息实例
     *
     * @param $notifiable
     * @return Message|TemplateMessage|ContentMessage
     */
    public function toSms($notifiable);
}