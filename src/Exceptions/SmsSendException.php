<?php

namespace Vicens\LaravelSms\Exceptions;

use Throwable;
use Vicens\LaravelSms\Contracts\Messages\Message;

class SmsSendException extends \Exception
{

    /**
     * 手机号
     *
     * @var string
     */
    protected $mobile;

    /**
     * 短信实例.
     *
     * @var Message
     */
    protected $smsMessage;

    /**
     * 短信服务返回结果
     *
     * @var array|string|null
     */
    protected $result;

    /**
     * RequestException constructor.
     *
     * @param string $mobile
     * @param Message $smsMessage
     * @param string $message
     * @param int $code
     * @param array $result
     * @param Throwable $throwable
     */
    public function __construct(
        $mobile, Message $smsMessage, $message = "", $code = 0, $result = null, Throwable $throwable = null
    )
    {

        $this->mobile = $mobile;

        $this->result = $result;

        $this->smsMessage = $smsMessage;

        parent::__construct($message, $code, $throwable);
    }

    /**
     * 获取解码后的结果.
     *
     * @return array|string|null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * 获取消息实例
     *
     * @return Message
     */
    public function getSmsMessage()
    {
        return $this->smsMessage;
    }

    /**
     * 获取手机号
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }
}