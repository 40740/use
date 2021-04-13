<?php

namespace OSS\Model;


/* 搜库资源网 soku.cc*
 * Class LifecycleRule
 * @package OSS\Model
 *
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/bucket/PutBucketLifecycle.html
 */
class LifecycleRule
{
    /* 搜库资源网 soku.cc*
     * 得到规则ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /* 搜库资源网 soku.cc*
     * @param string $id 规则ID
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /* 搜库资源网 soku.cc*
     * 得到文件前缀
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /* 搜库资源网 soku.cc*
     * 设置文件前缀
     *
     * @param string $prefix 文件前缀
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /* 搜库资源网 soku.cc*
     * Lifecycle规则的状态
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /* 搜库资源网 soku.cc*
     * 设置Lifecycle规则状态
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /* 搜库资源网 soku.cc*
     *
     * @return LifecycleAction[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /* 搜库资源网 soku.cc*
     * @param LifecycleAction[] $actions
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }


    /* 搜库资源网 soku.cc*
     * LifecycleRule constructor.
     *
     * @param string $id 规则ID
     * @param string $prefix 文件前缀
     * @param string $status 规则状态，可选[self::LIFECYCLE_STATUS_ENABLED, self::LIFECYCLE_STATUS_DISABLED]
     * @param LifecycleAction[] $actions
     */
    public function __construct($id, $prefix, $status, $actions)
    {
        $this->id = $id;
        $this->prefix = $prefix;
        $this->status = $status;
        $this->actions = $actions;
    }

    /* 搜库资源网 soku.cc*
     * @param \SimpleXMLElement $xmlRule
     */
    public function appendToXml(&$xmlRule)
    {
        $xmlRule->addChild('ID', $this->id);
        $xmlRule->addChild('Prefix', $this->prefix);
        $xmlRule->addChild('Status', $this->status);
        foreach ($this->actions as $action) {
            $action->appendToXml($xmlRule);
        }
    }

    private $id;
    private $prefix;
    private $status;
    private $actions = array();

    const LIFECYCLE_STATUS_ENABLED = 'Enabled';
    const LIFECYCLE_STATUS_DISABLED = 'Disabled';
}