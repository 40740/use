<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class ListPartsInfo
 * @package OSS\Model
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/multipart-upload/ListParts.html
 */
class ListPartsInfo
{

    /* 搜库资源网 soku.cc*
     * ListPartsInfo constructor.
     * @param string $bucket
     * @param string $key
     * @param string $uploadId
     * @param int $nextPartNumberMarker
     * @param int $maxParts
     * @param string $isTruncated
     * @param array $listPart
     */
    public function __construct($bucket, $key, $uploadId, $nextPartNumberMarker, $maxParts, $isTruncated, array $listPart)
    {
        $this->bucket = $bucket;
        $this->key = $key;
        $this->uploadId = $uploadId;
        $this->nextPartNumberMarker = $nextPartNumberMarker;
        $this->maxParts = $maxParts;
        $this->isTruncated = $isTruncated;
        $this->listPart = $listPart;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getUploadId()
    {
        return $this->uploadId;
    }

    /* 搜库资源网 soku.cc*
     * @return int
     */
    public function getNextPartNumberMarker()
    {
        return $this->nextPartNumberMarker;
    }

    /* 搜库资源网 soku.cc*
     * @return int
     */
    public function getMaxParts()
    {
        return $this->maxParts;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getIsTruncated()
    {
        return $this->isTruncated;
    }

    /* 搜库资源网 soku.cc*
     * @return array
     */
    public function getListPart()
    {
        return $this->listPart;
    }

    private $bucket = "";
    private $key = "";
    private $uploadId = "";
    private $nextPartNumberMarker = 0;
    private $maxParts = 0;
    private $isTruncated = "";
    private $listPart = array();
}