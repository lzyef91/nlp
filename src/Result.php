<?php

namespace Nldou\Nlp;

class Result
{
    protected $result;

    protected $methodsMap = [
        'AlibabaCloud\Client\Result\Result' => 'getAliyunResult'
    ];

    public function __construct($result, $version = '20190408')
    {
        $this->result = $result;

        // 腾讯云结果
        $tencentMap = [
            "TencentCloud\Nlp\V{$version}\Models\KeywordsExtractionResponse" => 'getTecentKeywordsResult',
            "TencentCloud\Nlp\V{$version}\Models\LexicalAnalysisResponse" => 'getTecentWordAnalysisResult',
            "TencentCloud\Nlp\V{$version}\Models\TextClassificationResponse" => 'getTecentClassifyResult',
            "TencentCloud\Nlp\V{$version}\Models\WordSimilarityResponse" => 'getTencentWordSimilarityResult',
            "TencentCloud\Nlp\V{$version}\Models\SentimentAnalysisResponse" => 'getTencentSentimentAnalysisResult',
            "TencentCloud\Nlp\V{$version}\Models\AutoSummarizationResponse" => 'getTencentSummaryResult'
        ];

        $this->methodsMap = \array_merge($this->methodsMap, $tencentMap);

    }

    public function get()
    {
        $cls = \ltrim(get_class($this->result), '\\');

        if (array_key_exists($cls, $this->methodsMap)) {
            $method = $this->methodsMap[$cls];
            $res = $this->$method($this->result);
        } else {
            $res = [];
        }

        return $res;
    }

    protected function getAliyunResult($result)
    {
        $res = $result->toArray();

        return $res['data'];
    }

    protected function getTecentKeywordsResult($result)
    {
        $res = [];

        foreach ($result->Keywords as $k) {
            $row['score'] = $k->Score;
            $row['word'] = $k->Word;
            $res[] = $row;
        }

        return $res;
    }

    protected function getTecentWordAnalysisResult($result)
    {
        $res = [];

        if (!is_null($result->NerTokens)) {
            foreach ($result->NerTokens as $n) {
                $row['offset'] = $n->BeginOffset;
                $row['length'] = $n->Length;
                $row['pos'] = $n->Type;
                $row['word'] = $n->Word;
                $res[] = $row;
            }
        }

        foreach ($result->PosTokens as $p) {
            $row['offset'] = $p->BeginOffset;
            $row['length'] = $p->Length;
            $row['pos'] = $p->Pos;
            $row['word'] = $p->Word;
            $res[] = $row;
        }

        return $res;
    }

    protected function getTecentClassifyResult($result)
    {
        $res = [];

        foreach ($result->Classes as $c) {
            $res[] = [
                [
                    'name' => $c->FirstClassName,
                    'prob' => $c->FirstClassProbability
                ],
                [
                    'name' => $c->SecondClassName,
                    'prob' => $c->SecondClassProbability
                ]
            ];
        }

        return $res;
    }

    protected function getTencentWordSimilarityResult($result)
    {
        return [
            'similarity' => $result->Similarity
        ];
    }

    protected function getTencentSentimentAnalysisResult($result)
    {
        return [
            'sentiment' => $result->Sentiment,
            'positive' => $result->Positive,
            'negative' => $result->Negative
        ];
    }

    protected function getTencentSummaryResult($result)
    {
        return [
            'summary' => $result->Summary
        ];
    }
}