<?php

namespace Nldou\Nlp\Client;

class Client
{
    protected $gateway;

    protected $accessKey;

    protected $accessSecret;

    protected $region;

    protected $client;

    protected $debug;

    protected $version;

    public function __construct($accessKey, $accessSecret, $debug, $region, $version)
    {
        $this->accessKey = $accessKey;
        $this->accessSecret = $accessSecret;
        $this->debug = $debug;
        $this->region = $region;
        $this->version = $this->formatVersion($version);
    }
}