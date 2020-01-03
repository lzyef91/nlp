<?php

namespace Nldou\Nlp;

use Nldou\Nlp\Exceptions\InvalidParamException;
use Nldou\Nlp\Client\AliyunClient;
use Nldou\Nlp\Client\TecentClient;

class Nlp
{
    /**
     * @var array $clients 客户端
     */
    protected $clients = [];

    /**
     * @var bool 调试模式
     */
    protected $debug;

    /**
     * @var string 当前网关
     */
    protected $gateway;

    /**
     * @var array 网关配置
     */
    protected $gatewayOptions;

    /**
     * @var array 可用网关
     */
    protected $gatewayAvailabe = [
        'aliyun' => AliyunClient::class,
        'tencent' => TecentClient::class
    ];

    /**
     * @var array 可用api接口
     */
    protected $apiMap = [
        'aliyun' => [
            'keywords',
            'wordSegment',
            'wordAnalysis'
        ],
        'tencent' => [
            'keywords',
            'wordAnalysis',
            'classify',
            'wordSimilarity',
            'sentimentAnalysis',
            'summary'
        ]
    ];

    public function __construct($gateway, $gatewayOptions, $debug)
    {
        $this->debug = $debug;
        $this->gateway = $gateway;
        $this->gatewayOptions = $gatewayOptions;
    }

    /**
     * 获取或切换网关
     * @param string $gateway
     *
     * @return $this
     */
    public function gateway($gateway = '')
    {
        if (!empty($gateway)) {
            $this->gateway = $gateway;
        }

        return $this;
    }

    /**
     * 获取客户端
     *
     * @return AliyunClient|TecentClient
     * @throws InvalidParamException
     */
    public function client()
    {
        if (array_key_exists($this->gateway, $this->clients)) {
            // 已存在客户端
            return $this->clients[$this->gateway];
        } elseif (array_key_exists($this->gateway, $this->gatewayAvailabe)) {
            // 客户端
            $class = $this->gatewayAvailabe[$this->gateway];
            $accessKey = $this->gatewayOptions[$this->gateway]['access_key'];
            $accessSecret = $this->gatewayOptions[$this->gateway]['access_secret'];
            $region = $this->gatewayOptions[$this->gateway]['region'];
            $version = $this->gatewayOptions[$this->gateway]['version'];
            // 加入客户端列表
            return $this->clients[$this->gateway] = new $class($accessKey, $accessSecret, $this->debug, $region, $version);
        } else {
            throw new InvalidParamException('gateway not exist');
        }
    }

    public function __call($name, $arguments)
    {
        if (!in_array($name, $this->apiMap[$this->gateway])) {
            throw new InvalidParamException('api not exist');
        }

        return $this->client()->$name(...$arguments);

    }
}