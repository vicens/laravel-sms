<?php

namespace Vicens\LaravelSms\Messages;

use Vicens\LaravelSms\Contracts\Messages\TemplateMessage as TemplateMessageInterface;

class TemplateMessage implements TemplateMessageInterface
{
    /**
     * 模板ID
     *
     * @var string
     */
    protected $template;

    /**
     * 模板参数
     *
     * @var array
     */
    protected $parameters;

    /**
     * TemplateMessage constructor.
     *
     * @param string $template
     * @param array $parameters
     */
    public function __construct($template, array $parameters = [])
    {
        $this->template = $template;

        $this->parameters = $parameters;
    }

    /**
     * 返回模板ID
     *
     * @return string
     */
    public function getTemplateId()
    {
        return $this->template;
    }

    /**
     * 返回模板参数
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}