<?php
/* 搜库资源网 soku.cc
* 任务，侧边栏
*/
$name = zrz_get_credit_settings('zrz_credit_name');
?>
<section id="pages-2" class="widget widget_write mar10-b">
    <h2 class="widget-title l1 pd10 box-header">提示</h2>
    <div class="box">
        <ul>
            <li>
                <b>完成任务的奖励</b>
                <p>您在网站上的互动都将得到<?php echo $name; ?>奖励，通过<?php echo $name; ?>的增长，您的等级也会得到提升。</p>
            </li>
            <li>
                <b>奖励规则</b>
                <p>并不是每次互动都会得到奖励，如果您今天的任务次数已经达成，将不会再获得<?php echo $name; ?>奖励，不过对您在网站上的互动没有任何影响。</p>
            </li>
        <ul>
    </div>
</section>
