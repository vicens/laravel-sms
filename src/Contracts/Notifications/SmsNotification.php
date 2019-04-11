<?php

namespace Vicens\LaravelSms\Contracts\Notifications;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notifiable;
use Vicens\LaravelSms\Contracts\Messages\Message;

interface SmsNotification
{
    /**
     * 返回消息实例
     * @param Notifiable|AnonymousNotifiable $notifiable
     * @return Message
     */
    public function toSms($notifiable);
}