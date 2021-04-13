<?php

namespace OSS\Result;

use OSS\Model\BucketInfo;
use OSS\Model\BucketListInfo;

/* 搜库资源网 soku.cc*
 * Class ListBucketsResult
 *
 * @package OSS\Result
 */
class ListBucketsResult extends Result
{
    /* 搜库资源网 soku.cc*
     * @return BucketListInfo
     */
    protected function parseDataFromResponse()
    {
        $bucketList = array();
        $content = $this->rawResponse->body;
        $xml = new \SimpleXMLElement($content);
        if (isset($xml->Buckets) && isset($xml->Buckets->Bucket)) {
            foreach ($xml->Buckets->Bucket as $bucket) {
                $bucketInfo = new BucketInfo(strval($bucket->Location),
                    strval($bucket->Name),
                    strval($bucket->CreationDate));
                $bucketList[] = $bucketInfo;
            }
        }
        return new BucketListInfo($bucketList);
    }
}