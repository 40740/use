<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class ObjectListInfo
 *
 * ListObjects接口返回数据
 *
 * @package OSS\Model
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/bucket/GetBucket.html
 */
class ObjectListInfo
{
    /* 搜库资源网 soku.cc*
     * ObjectListInfo constructor.
     *
     * @param string $bucketName
     * @param string $prefix
     * @param string $marker
     * @param string $nextMarker
     * @param string $maxKeys
     * @param string $delimiter
     * @param null $isTruncated
     * @param array $objectList
     * @param array $prefixList
     */
    public function __construct($bucketName, $prefix, $marker, $nextMarker, $maxKeys, $delimiter, $isTruncated, array $objectList, array $prefixList)
    {
        $this->bucketName = $bucketName;
        $this->prefix = $prefix;
        $this->marker = $marker;
        $this->nextMarker = $nextMarker;
        $this->maxKeys = $maxKeys;
        $this->delimiter = $delimiter;
        $this->isTruncated = $isTruncated;
        $this->objectList = $objectList;
        $this->prefixList = $prefixList;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getBucketName()
    {
        return $this->bucketName;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getMarker()
    {
        return $this->marker;
    }

    /* 搜库资源网 soku.cc*
     * @return int
     */
    public function getMaxKeys()
    {
        return $this->maxKeys;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /* 搜库资源网 soku.cc*
     * @return mixed
     */
    public function getIsTruncated()
    {
        return $this->isTruncated;
    }

    /* 搜库资源网 soku.cc*
     * 返回ListObjects接口返回数据中的ObjectInfo列表
     *
     * @return ObjectInfo[]
     */
    public function getObjectList()
    {
        return $this->objectList;
    }

    /* 搜库资源网 soku.cc*
     * 返回ListObjects接口返回数据中的PrefixInfo列表
     *
     * @return PrefixInfo[]
     */
    public function getPrefixList()
    {
        return $this->prefixList;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getNextMarker()
    {
        return $this->nextMarker;
    }

    private $bucketName = "";
    private $prefix = "";
    private $marker = "";
    private $nextMarker = "";
    private $maxKeys = 0;
    private $delimiter = "";
    private $isTruncated = null;
    private $objectList = array();
    private $prefixList = array();
}