<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class PartInfo
 * @package OSS\Model
 */
class PartInfo
{
    /* 搜库资源网 soku.cc*
     * PartInfo constructor.
     *
     * @param int $partNumber
     * @param string $lastModified
     * @param string $eTag
     * @param int $size
     */
    public function __construct($partNumber, $lastModified, $eTag, $size)
    {
        $this->partNumber = $partNumber;
        $this->lastModified = $lastModified;
        $this->eTag = $eTag;
        $this->size = $size;
    }

    /* 搜库资源网 soku.cc*
     * @return int
     */
    public function getPartNumber()
    {
        return $this->partNumber;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getETag()
    {
        return $this->eTag;
    }

    /* 搜库资源网 soku.cc*
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    private $partNumber = 0;
    private $lastModified = "";
    private $eTag = "";
    private $size = 0;
}