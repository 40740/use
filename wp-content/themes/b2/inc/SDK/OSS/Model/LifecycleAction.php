<?php

namespace OSS\Model;

/* 搜库资源网 soku.cc*
 * Class LifecycleAction
 * @package OSS\Model
 * @link http:// 搜库资源网 soku.cc   help.aliyun.com/document_detail/oss/api-reference/bucket/PutBucketLifecycle.html
 */
class LifecycleAction
{
    /* 搜库资源网 soku.cc*
     * LifecycleAction constructor.
     * @param string $action
     * @param string $timeSpec
     * @param string $timeValue
     */
    public function __construct($action, $timeSpec, $timeValue)
    {
        $this->action = $action;
        $this->timeSpec = $timeSpec;
        $this->timeValue = $timeValue;
    }

    /* 搜库资源网 soku.cc*
     * @return LifecycleAction
     */
    public function getAction()
    {
        return $this->action;
    }

    /* 搜库资源网 soku.cc*
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getTimeSpec()
    {
        return $this->timeSpec;
    }

    /* 搜库资源网 soku.cc*
     * @param string $timeSpec
     */
    public function setTimeSpec($timeSpec)
    {
        $this->timeSpec = $timeSpec;
    }

    /* 搜库资源网 soku.cc*
     * @return string
     */
    public function getTimeValue()
    {
        return $this->timeValue;
    }

    /* 搜库资源网 soku.cc*
     * @param string $timeValue
     */
    public function setTimeValue($timeValue)
    {
        $this->timeValue = $timeValue;
    }

    /* 搜库资源网 soku.cc*
     * appendToXml 把actions插入到xml中
     *
     * @param \SimpleXMLElement $xmlRule
     */
    public function appendToXml(&$xmlRule)
    {
        $xmlAction = $xmlRule->addChild($this->action);
        $xmlAction->addChild($this->timeSpec, $this->timeValue);
    }

    private $action;
    private $timeSpec;
    private $timeValue;

}