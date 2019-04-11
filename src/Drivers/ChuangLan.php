<?php

namespace Vicens\LaravelSms\Drivers;

use Vicens\LaravelSms\Contracts\Messages\ContentMessage;

class ChuangLan extends AbstractDriver
{
    /**
     * HTTP address
     */
    const HTTP_URL = 'http://smssh1.253.com/msg/send/json';

    /**
     * HTTPs address
     */
    const HTTPS_URL = 'https://smssh1.253.com/msg/send/json';

    /**
     * Use HTTPs.
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * App Key
     *
     * @var string
     */
    protected $account;

    /**
     * App Secret
     *
     * @var string
     */
    protected $password;

    /**
     * UserMessage sign name.
     *
     * @var string
     */
    protected $sign;

    /**
     * 发送短信
     *
     * @param string $mobile
     * @param ContentMessage $message
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Vicens\LaravelSms\Exceptions\SmsSendException
     */
    public function send($mobile, ContentMessage $message)
    {
        $params = [
            'account' => $this->account,
            'password' => $this->password,
            'msg' => $message->getContent(),
            'phone' => $mobile
        ];

        $parameters = $message->getParameters();
        if (isset($parameters['extend'])) {
            $params['extend'] = $parameters['extend'];
        }

        // Send request.
        $result = $this->post($this->getUrl(), [], array(
            'body' => json_encode($params)
        ));

        // Has error.
        if (!$result || (isset($result['code']) && $result['code'] != '0')) {

            $this->sendException(
                $mobile,
                $message,
                $result['errorMsg'] ?? 'unknown error.',
                $result['code'] ?? -1,
                $result
            );
        }

        return true;
    }

    /**
     * Get the API address.
     *
     * @return string
     */
    protected function getUrl()
    {
        return $this->secure ? static::HTTPS_URL : static::HTTP_URL;
    }
}