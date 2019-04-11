<?php

namespace Vicens\LaravelSms\Contracts\Messages;

interface ContentMessage extends Message
{
    /**
     * 返回模板内容
     *
     * @return string
     */
    public function getContent();

    /**
     * 返回内容参数
     *
     * @return array
     */
    public function getParameters();
}