<?php

namespace Vicens\LaravelSms\Drivers;

use Vicens\LaravelSms\Contracts\Messages\TemplateMessage;

class Aliyun extends AbstractDriver
{

    const DOMAIN = 'dysmsapi.aliyuncs.com';

    /**
     * Access key id.
     *
     * @var static
     */
    protected $accessKeyId;

    /**
     * Access key secret.
     *
     * @var string
     */
    protected $accessKeySecret;

    /**
     * 签名
     *
     * @var string
     */
    protected $sign;

    /**
     * Use HTTPs.
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * Region.
     *
     * @var string
     */
    protected $region = 'cn-hangzhou';

    /**
     * Version.
     *
     * @var string
     */
    protected $version = '2017-05-25';

    /**
     * 发送短信
     *
     * @param $mobile
     * @param TemplateMessage $message
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Vicens\LaravelSms\Exceptions\SmsSendException
     */
    public function send($mobile, TemplateMessage $message)
    {

        $region = $this->getMessageProperty($message, 'region', $this->region);
        $version = $this->getMessageProperty($message, 'version', $this->version);
        $outId = $this->getMessageProperty($message, 'outId');
        $smsUpExtendCode = $this->getMessageProperty($message, 'smsUpExtendCode');

        $params = array_filter([
            "SignatureMethod" => 'HMAC-SHA1',
            "SignatureNonce" => uniqid(mt_rand(0, 0xffff), true),
            "SignatureVersion" => '1.0',
            "Timestamp" => gmdate('Y-m-d\TH:i:s\Z'),
            "Format" => 'JSON',
            "RegionId" => $region,
            "Action" => 'SendSms',
            "Version" => $version,
            'OutId' => $outId,
            'SmsUpExtendCode' => $smsUpExtendCode,
            'AccessKeyId' => $this->accessKeyId,
            'PhoneNumbers' => $mobile,
            'SignName' => $this->sign,
            'TemplateCode' => $message->getTemplateId(),
            'TemplateParam' => json_encode($message->getParameters(), JSON_UNESCAPED_UNICODE),
        ]);

        $url = ($this->secure ? 'https' : 'http') . '://' . static::DOMAIN;

        ksort($params);

        $queryString = '';
        foreach ($params as $key => $value) {
            $queryString .= '&' . $this->encode($key) . '=' . $this->encode($value);
        }

        $result = $this->get($url, [], [
            'query' => 'Signature=' . $this->buildSignature($queryString) . $queryString
        ]);

        if (!$result || $result['Code'] !== 'OK') {
            $error = array_key_exists('Message', $result) ? $result['Message'] : '';

            $this->sendException($mobile, $message, $error, 0, $result);
        }

        return true;
    }

    /**
     * Generate the signature.
     *
     * @param string $queryString
     * @return string
     */
    protected function buildSignature($queryString)
    {

        $stringToSign = 'GET&%2F&' . $this->encode(substr($queryString, 1));

        $sign = base64_encode(hash_hmac('sha1', $stringToSign, $this->accessKeySecret . '&', true));

        return $this->encode($sign);
    }

    /**
     * Encode signature.
     *
     * @param string $str
     * @return string
     */
    private function encode($str)
    {
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getUrl(array $params)
    {
        $url = ($this->secure ? 'https' : 'http') . '://' . static::DOMAIN;

        ksort($params);

        $queryString = "";
        foreach ($params as $key => $value) {
            $queryString .= "&" . $this->encode($key) . "=" . $this->encode($value);
        }

        return $url . '/?Signature=' . $this->buildSignature($queryString) . $queryString;
    }
}
