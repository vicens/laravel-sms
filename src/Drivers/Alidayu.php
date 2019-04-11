<?php

namespace Vicens\LaravelSms\Drivers;

use Vicens\LaravelSms\Contracts\Messages\TemplateMessage;
use Vicens\LaravelSms\Contracts\Drivers\TemplateMessageDriver;

class Alidayu extends AbstractDriver implements TemplateMessageDriver
{
    /**
     * HTTP address.
     */
    const HTTP_URL = 'http://gw.api.taobao.com/router/rest';

    /**
     * HTTPs address.
     */
    const HTTPS_URL = 'https://eco.taobao.com/router/rest';

    /**
     * HTTP address of sandbox.
     */
    const SANDBOX_HTTP_URL = 'http://gw.api.tbsandbox.com/router/rest';

    /**
     * HTTPs address of sandbox;
     */
    const SANDBOX_HTTPS_URL = 'https://gw.api.tbsandbox.com/router/rest';

    /**
     * The API method.
     */
    const METHOD = 'alibaba.aliqin.fc.sms.num.send';

    /**
     * Use sandbox.
     *
     * @var bool
     */
    protected $sandbox = false;

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
    protected $appKey;

    /**
     * App Secret
     *
     * @var string
     */
    protected $appSecret;

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
     * @param TemplateMessage $message
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Vicens\LaravelSms\Exceptions\SmsSendException
     */
    public function send($mobile, TemplateMessage $message)
    {

        $params = [
            'app_key' => $this->appKey,
            'sign_method' => 'md5',
            'timestamp' => date('Y-m-d H:i:s'),
            'format' => 'json',
            'v' => '2.0',
            'method' => static::METHOD,
            'sms_type' => 'normal',
            'rec_num' => $mobile,
            'sms_template_code' => $message->getTemplateId(),
            'sms_free_sign_name' => $this->sign,
            'sms_param' => json_encode($message->getParameters())
        ];

        // Make signature.
        $params['sign'] = $this->buildSignature($params);

        // Send request.
        $result = $this->get($this->getUrl(), $params);

        // Has error.
        if (!$result || (isset($result['error_response']) && !empty($result['error_response']))) {

            $errorResponse = $result['error_response'];

            $error = 'Unknown error';
            $code = 0;

            if (isset($errorResponse['sub_msg'])) {

                $error = $errorResponse['sub_msg'];

            } elseif (isset($errorResponse['msg'])) {

                $error = $errorResponse['msg'];
            }

            if (isset($errorResponse['sub_code'])) {

                $code = $errorResponse['sub_code'];

            } elseif (isset($errorResponse['code'])) {

                $code = $errorResponse['code'];
            }

            $this->sendException($mobile, $message, $error, $code, $result);

        }

        return true;
    }

    /**
     * Generate the signature.
     *
     * @param array $parameters
     * @return string
     */
    protected function buildSignature(array $parameters)
    {
        // Sort by key.
        ksort($parameters);

        $paramArray = [];
        foreach ($parameters as $key => $value) {
            $paramArray[] = $key . $value;
        }
        $string = $this->appSecret . implode('', $paramArray) . $this->appSecret;

        return strtoupper(md5($string));
    }

    /**
     * Get the API address.
     *
     * @return string
     */
    protected function getUrl()
    {

        if (!($this->secure && $this->sandbox)) {
            return static::HTTP_URL;
        } else {
            if (!$this->secure && $this->sandbox) {
                return static::SANDBOX_HTTP_URL;
            } else {
                if ($this->secure && $this->sandbox) {
                    return static::SANDBOX_HTTPS_URL;
                } else {
                    return static::HTTPS_URL;
                }
            }
        }
    }


}