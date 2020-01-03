<?php

namespace Nldou\Nlp\Client;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\AlibabaCloudException;
use Nldou\Nlp\Result;
use Nldou\Nlp\Exceptions\SDKException;

class AliyunClient extends Client
{
    protected $gateway = 'aliyun';

    public function formatVersion($version)
    {
        return (new \DateTime($version))->format('Y-m-d');
    }

    public function client()
    {
        if (is_null($this->client)) {
            $this->client = AlibabaCloud::accessKeyClient($this->accessKey, $this->accessSecret)
                ->regionId($this->region)->asDefaultClient();
        }

        return $this->client;
    }


    /**
     * 关键词提取(电商领域)
     * @param string $text 待处理文本（90个字）
     *
     * @return Result
     */
    public function keywords($text)
    {
        $this->client();

        $body = \json_encode([
            'text' => $text,
            'lang' => 'ZH'
        ]);

        try {
            $res = AlibabaCloud::roa()->product('nlp')
            ->pathPattern('/nlp/api/kwe/ecommerce')
            ->method('POST')
            ->body($body)
            ->version($this->version)
            ->debug($this->debug)
            ->request();
        } catch (AlibabaCloudException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }


        return new Result($res);
    }

    /**
     * 分词
     * @param string $text 待处理文本（175个字）
     *
     * @return Result
     */
    public function wordSegment($text)
    {
        $this->client();

        $body = \json_encode([
            'text' => $text,
            'lang' => 'ZH'
        ]);

        try {
            $res = AlibabaCloud::roa()->product('nlp')
                    ->pathPattern('/nlp/api/wordsegment/general')
                    ->method('POST')
                    ->body($body)
                    ->debug($this->debug)
                    ->version($this->version)
                    ->request();
        } catch (AlibabaCloudException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }

        return new Result($res);
    }

    /**
     * 词性标注
     * @param string $text 待处理文本（175个字）
     *
     * @return Result
     */
    public function wordAnalysis($text)
    {
        $this->client();

        $body = \json_encode([
            'text' => $text
        ]);

        try {
            $res = AlibabaCloud::roa()->product('nlp')
                    ->pathPattern('/nlp/api/wordpos/general')
                    ->method('POST')
                    ->body($body)
                    ->debug($this->debug)
                    ->version($this->version)
                    ->request();
        } catch (AlibabaCloudException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }

        return new Result($res);
    }


}