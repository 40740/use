<?php
/* 搜库资源网 soku.cc话题*/
if(class_exists( 'bbPress' )){
    $user_id = get_query_var('author');
    $reply_count = count_user_posts($user_id, 'reply', false);
    $nub = get_option( '_bbp_replies_per_page', 15 );
    $ipages = ceil( $reply_count / $nub);
    $msg = zrz_get_topic_or_reply(1,$user_id,'reply',true);
    if($msg){
        echo '<div id="user-topic">
        <div class="box-header pd10 b-b"><span v-text="uName"></span>的跟帖</div>
                <div ref="listTopic">
                    '.$msg.'
                </div>
                <page-nav class="b-t" :nav-type="\'userReply\'" :paged="paged" :pages="'.$ipages.'" :show-type="\'p\'"></page-nav>
            </div>';
    }else{
        echo '<div class="loading-dom pos-r"><div class="lm"><i class="iconfont zrz-icon-font-wuneirong"></i><p class="mar10-t">没有跟帖</p></div></div>';
    }

}
