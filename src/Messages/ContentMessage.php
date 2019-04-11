<?php

namespace Vicens\LaravelSms\Messages;

use Vicens\LaravelSms\Contracts\Messages\ContentMessage as ContentMessageInterface;

class ContentMessage implements ContentMessageInterface
{
    /**
     * 短信内容
     *
     * @var string
     */
    protected $content;

    /**
     * 内容参数
     *
     * @var array
     */
    protected $parameters;

    /**
     * ContentMessage constructor.
     *
     * @param string $content
     * @param array $parameters
     */
    public function __construct($content, array $parameters = [])
    {
        $this->content = $content;

        $this->parameters = $parameters;
    }

    /**
     * 返回短信内容
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 返回内容参数
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}