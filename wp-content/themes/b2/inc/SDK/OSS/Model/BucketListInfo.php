<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class BucketListInfo
 *
 * ListBuckets接口返回的数据类型
 *
 * @package OSS\Model
 */
class BucketListInfo
{
    /* 搜库资源网 soku.cc*
     * BucketListInfo constructor.
     * @param array $bucketList
     */
    public function __construct(array $bucketList)
    {
        $this->bucketList = $bucketList;
    }

    /* 搜库资源网 soku.cc*
     * 得到BucketInfo列表
     *
     * @return BucketInfo[]
     */
    public function getBucketList()
    {
        return $this->bucketList;
    }

    /* 搜库资源网 soku.cc*
     * BucketInfo信息列表
     *
     * @var array
     */
    private $bucketList = array();
}