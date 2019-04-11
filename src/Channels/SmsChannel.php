<?php

namespace Vicens\LaravelSms\Channels;

use Vicens\LaravelSms\Contracts\Messages\Message;
use Vicens\LaravelSms\Contracts\Notifications\SmsNotification;
use Vicens\LaravelSms\Facades\Sms;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notifiable;

class SmsChannel
{

    /**
     * 发送短信
     *
     * @param AnonymousNotifiable|Notifiable $notifiable
     * @param SmsNotification $notification
     *
     * @return bool
     */
    public function send($notifiable, SmsNotification $notification)
    {
        $message = $this->getMessageFromNotifiable($notifiable, $notification);

        $driver = $this->getDriverForMessage($message);

        return $this->sendMessage($this->getMobileFromNotifiable($notifiable), $message, $driver);
    }

    /**
     * 获取消息的发送驱动
     *
     * @param Message $message
     * @return string
     */
    protected function getDriverForMessage(Message $message)
    {
        return method_exists($message, 'driver') ? $message->driver() : Sms::getDefaultDriver();
    }


    /**
     * 获取手机号
     *
     * @param AnonymousNotifiable|Notifiable $notifiable $notifiable
     * @return string
     */
    protected function getMobileFromNotifiable($notifiable)
    {
        return $notifiable->routeNotificationFor('sms');
    }

    /**
     * 获取消息实例
     *
     * @param AnonymousNotifiable|Notifiable $notifiable
     * @param SmsNotification $notification
     * @return Message
     */
    protected function getMessageFromNotifiable($notifiable, SmsNotification $notification)
    {
        return $notification->toSms($notifiable);
    }

    /**
     * 发送消息
     *
     * @param string $mobile 手机号
     * @param Message $message 消息
     * @param string $driver 驱动
     * @return boolean
     */
    protected function sendMessage($mobile, Message $message, $driver = null)
    {
        return Sms::driver($driver)->send($mobile, $message);
    }
}