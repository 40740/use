<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class PrefixInfo
 *
 * listObjects接口中返回的Prefix列表中的类
 * listObjects接口返回数据中包含两个Array:
 * 一个是拿到的Object列表【可以理解成对应文件系统中的文件列表】
 * 一个是拿到的Prefix列表【可以理解成对应文件系统中的目录列表】
 *
 * @package OSS\Model
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/bucket/GetBucket.html
 */
class PrefixInfo
{
    /* 搜库资源网 soku.cc*
     * PrefixInfo constructor.
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    private $prefix;
}