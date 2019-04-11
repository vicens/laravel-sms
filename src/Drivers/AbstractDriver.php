<?php

namespace Vicens\LaravelSms\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Vicens\LaravelSms\Contracts\Drivers\Driver;
use Vicens\LaravelSms\Contracts\Messages\Message;
use Vicens\LaravelSms\Exceptions\SmsSendException;

abstract class AbstractDriver implements Driver
{
    /**
     * Converters
     */
    const CONVERTERS = [
        'application/json text/json' => 'parseJSON',
        'text/xml xml' => 'parseXML'
    ];

    /**
     * Timeout.
     *
     * @var int
     */
    protected $timeout = 60;

    /**
     * Provider constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->setConfig($config);
        }
    }

    /**
     * Set the config
     *
     * @param array $config
     * @return $this
     */
    protected function setConfig(array $config)
    {
        // Auto set Configure
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * 发送请求
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request($method, $url, array $options = [])
    {
        $client = $this->getHttpClient();

        try {
            $response = $client->request($method, $url, $options);
        } catch (ClientException $exception) {
            if (!$exception->hasResponse()) {
                throw $exception;
            }

            $response = $exception->getResponse();
        }

        return $this->decodeResponse($response);
    }

    /**
     * 发起GET请求
     *
     * @param string $url
     * @param array $data
     * @param array $options
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function get($url, array $data = [], array $options = [])
    {
        return $this->request('get', $url, array_merge([
            'query' => $data
        ], $options));
    }

    /**
     * 发起POST请求
     *
     * @param string $url
     * @param array $data
     * @param array $options
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function post($url, array $data = [], array $options = [])
    {
        if (!empty($data)) {
            $options['form_params'] = $data;
        }

        return $this->request('post', $url, $options);
    }

    /**
     * Decode the response.
     *
     * @param ResponseInterface $response
     * @return array|string
     */
    protected function decodeResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $content = $response->getBody()->getContents();

        if ($content) {
            foreach (static::CONVERTERS as $types => $parser) {
                $types = explode(' ', $types);
                foreach ($types as $type) {
                    if (strpos($contentType, $type) === false) {
                        continue 2;
                    }
                }

                return $this->$parser($content);
            }
        }

        return $this->defaultParser($content);
    }

    /**
     * The Default parser.
     *
     * @param string $content
     * @return array
     */
    protected function defaultParser($content)
    {
        return $this->parseJSON($content);
    }

    /**
     * Get the HTTP Client for the provider.
     *
     * @return Client
     */
    protected function getHttpClient()
    {
        return new Client([
            'timeout' => $this->timeout
        ]);
    }

    /**
     * Parse JSON.
     *
     * @param string $content
     * @return array
     */
    protected function parseJSON($content)
    {
        return json_decode($content, true);
    }

    /**
     * Parse XML
     *
     * @param string $content
     * @return array
     */
    protected function parseXML($content)
    {
        return (array)simplexml_load_string($content);
    }

    /**
     * @param string $mobile
     * @param Message $message
     * @param string $error
     * @param int $code
     * @param array|string $result
     * @param \Throwable|null $throwable
     * @throws SmsSendException
     */
    protected function sendException(
        $mobile,
        Message $message,
        $error = '',
        $code = 0,
        $result = null,
        \Throwable $throwable = null
    ) {
        throw new SmsSendException($mobile, $message, $error, (int)$code, $result, $throwable);
    }
}
