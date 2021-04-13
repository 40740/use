<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class ListMultipartUploadInfo
 * @package OSS\Model
 *
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/multipart-upload/ListMultipartUploads.html
 */
class ListMultipartUploadInfo
{
    /* 搜库资源网 soku.cc*
     * ListMultipartUploadInfo constructor.
     *
     * @param string $bucket
     * @param string $keyMarker
     * @param string $uploadIdMarker
     * @param string $nextKeyMarker
     * @param string $nextUploadIdMarker
     * @param string $delimiter
     * @param string $prefix
     * @param int $maxUploads
     * @param string $isTruncated
     * @param array $uploads
     */
    public function __construct($bucket, $keyMarker, $uploadIdMarker, $nextKeyMarker, $nextUploadIdMarker, $delimiter, $prefix, $maxUploads, $isTruncated, array $uploads)
    {
        $this->bucket = $bucket;
        $this->keyMarker = $keyMarker;
        $this->uploadIdMarker = $uploadIdMarker;
        $this->nextKeyMarker = $nextKeyMarker;
        $this->nextUploadIdMarker = $nextUploadIdMarker;
        $this->delimiter = $delimiter;
        $this->prefix = $prefix;
        $this->maxUploads = $maxUploads;
        $this->isTruncated = $isTruncated;
        $this->uploads = $uploads;
    }

    /* 搜库资源网 soku.cc*
     * 得到bucket名称
     *
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getKeyMarker()
    {
        return $this->keyMarker;
    }

    /* 搜库资源网 soku.cc*
     *
     * @return string
     */
    public function getUploadIdMarker()
    {
        return $this->uploadIdMarker;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getNextKeyMarker()
    {
        return $this->nextKeyMarker;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getNextUploadIdMarker()
    {
        return $this->nextUploadIdMarker;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /* 搜库资源网 soku.cc*
     * @return int
     */
    public function getMaxUploads()
    {
        return $this->maxUploads;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getIsTruncated()
    {
        return $this->isTruncated;
    }

    /* 搜库资源网 soku.cc*
     * @return UploadInfo[]
     */
    public function getUploads()
    {
        return $this->uploads;
    }

    private $bucket = "";
    private $keyMarker = "";
    private $uploadIdMarker = "";
    private $nextKeyMarker = "";
    private $nextUploadIdMarker = "";
    private $delimiter = "";
    private $prefix = "";
    private $maxUploads = 0;
    private $isTruncated = "false";
    private $uploads = array();
}