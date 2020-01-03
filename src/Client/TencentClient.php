<?php

namespace Nldou\Nlp\Client;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use Nldou\Nlp\Result;
use Nldou\Nlp\Exceptions\SDKException;
use TencentCloud\Nlp\V20190408\NlpClient;
use TencentCloud\Nlp\V20190408\Models\WordSimilarityRequest;

class TecentClient extends Client
{
    protected $gateway = 'tencent';

    public function formatVersion($version)
    {
        return (new \DateTime($version))->format('Ymd');
    }

    public function client()
    {
        if (is_null($this->client)) {
            $cred = new Credential($this->accessKey, $this->accessSecret);
            $clientCls = "\TencentCloud\Nlp\V{$this->version}\NlpClient";
            $this->client = new $clientCls($cred, $this->region);
        }

        return $this->client;
    }


    /**
     * 关键词提取
     * @param string $text 待处理文本
     * @param int $num 关键词个数上限
     *
     * @return Result
     */
    public function keywords($text, $num = 10)
    {
        $reqCls = "\TencentCloud\Nlp\V{$this->version}\Models\KeywordsExtractionRequest";
        $req = new $reqCls();

        // 待处理文本
        $req->setText($text);

        // 中心词个数上限
        $req->setNum($num);

        try {
            $res = $this->client()->KeywordsExtraction($req);
        } catch (TencentCloudSDKException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }

        return new Result($res, $this->version);
    }

    /**
     * 词性标注
     * @param string $text 待处理文本
     * @param int $flag
     * 1、高精度（混合粒度分词能力）；
     * 2、高性能（单粒度分词能力）；
     *
     * @return Result
     */
    public function wordAnalysis($text, $flag = 2)
    {
        $reqCls = "\TencentCloud\Nlp\V{$this->version}\Models\LexicalAnalysisRequest";
        $req = new $reqCls();

        $req->setText($text);

        $req->setFlag($flag);

        try {
            $res = $this->client()->LexicalAnalysis($req);
        } catch (TencentCloudSDKException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }

        return new Result($res, $this->version);
    }

    /**
     * 文本分类
     * @param string $text 待处理文本
     * @param int $flag
     * 1、通用领域
     * 2、新闻领域
     *
     * @return Result
     */
    public function classify($text, $flag = 1)
    {
        $reqCls = "TencentCloud\Nlp\V{$this->version}\Models\TextClassificationRequest";
        $req = new $reqCls();

        $req->setText($text);

        $req->setFlag($flag);

        try {
            $res = $this->client()->TextClassification($req);
        } catch (TencentCloudSDKException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }

        return new Result($res, $this->version);

    }

    /**
     * 词相似度
     * @param string $src 源词
     * @param string $target 目标词
     *
     * @return Result
     */
    public function wordSimilarity($src, $target)
    {
        $reqCls = "TencentCloud\Nlp\V{$this->version}\Models\WordSimilarityRequest";
        $req = new $reqCls();

        $req->setSrcWord($src);

        $req->setTargetWord($target);

        try {
            $res = $this->client()->WordSimilarity($req);
        } catch (TencentCloudSDKException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }

        return new Result($res, $this->version);

    }

    /**
     * 情感分析
     * @param string $text 待处理文本 200个字以内
     * @param int $flag
     * 1、商品评论类
     * 2、社交类
     * 3、美食酒店类
     * 4、通用领域类
     *
     * @return Result
     */
    public function sentimentAnalysis($text, $flag = 4)
    {
        $reqCls = "TencentCloud\Nlp\V{$this->version}\Models\SentimentAnalysisRequest";
        $req = new $reqCls();

        $req->setText($text);

        $req->setFlag($flag);

        try {
            $res = $this->client()->SentimentAnalysis($req);
        } catch (TencentCloudSDKException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }

        return new Result($res, $this->version);
    }

    /**
     * 文本摘要
     * @param string $text 待处理文本
     * @param int $len 指定摘要长度
     * 为保证摘要的可读性，最终生成的摘要长度并不会严格遵循这个值，会有略微的浮动
     *
     * @return Result
     */
    public function summary($text, $len = 200)
    {
        $reqCls = "TencentCloud\Nlp\V{$this->version}\Models\AutoSummarizationRequest";
        $req = new $reqCls();

        $req->setText($text);

        $req->setLength($len);

        try {
            $res = $this->client()->AutoSummarization($req);
        } catch (TencentCloudSDKException $e) {
            throw new SDKException($e->getMessage(), $e->getCode());
        }

        return new Result($res, $this->version);
    }
}