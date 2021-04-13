<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class UploadInfo
 *
 * ListMultipartUpload接口得到的UploadInfo
 *
 * @package OSS\Model
 */
class UploadInfo
{
    /* 搜库资源网 soku.cc*
     * UploadInfo constructor.
     *
     * @param string $key
     * @param string $uploadId
     * @param string $initiated
     */
    public function __construct($key, $uploadId, $initiated)
    {
        $this->key = $key;
        $this->uploadId = $uploadId;
        $this->initiated = $initiated;
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
     * @return string
     */
    public function getInitiated()
    {
        return $this->initiated;
    }

    private $key = "";
    private $uploadId = "";
    private $initiated = "";
}