<?php

namespace Vicens\LaravelSms\Contracts\Messages;

interface TemplateMessage extends Message
{
    /**
     * 返回模板ID
     *
     * @return string
     */
    public function getTemplateId();

    /**
     * 返回模板参数
     *
     * @return array
     */
    public function getParameters();
}