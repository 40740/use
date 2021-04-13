<?php 
	global $current_user;
	$moneyName = get_option('ice_name_alipay');
	$okMoney = erphpGetUserOkMoney();
?>
<div class="main">
	<div class="container container-user">
	  <div class="userside">
	    <div class="usertitle"> 
	    	<a href="javascript:;" class="edit-avatar" evt="user.avatar.submit" title="点击修改头像"><?php echo get_avatar($current_user->ID,50);?></a>
	        <h2><?php echo $current_user->nickname;?></h2>
	        <?php
	        	if(_MBT('checkin')) {
	        		$gift = _MBT('checkin_gift')?_MBT('checkin_gift'):0;
			        if(MBThemes_check_checkin($current_user->ID)){
			      		echo '<div class="mobantu-check"><a href="javascript:;" class="usercheck active">已签到</a><p>每日签到送'.$gift.$moneyName.'</p></div>';
			        }else{
			      		echo '<div class="mobantu-check"><a href="javascript:;" class="usercheck checkin">今日签到</a><p>每日签到送'.$gift.$moneyName.'</p></div>';
			        }
			    }
	        ?>
	        <form id="uploadphoto" action="<?php echo get_bloginfo('template_url').'/action/photo.php';?>" method="post" enctype="multipart/form-data" style="display:none;">
	            <input type="file" id="avatarphoto" name="avatarphoto" accept="image/png, image/jpeg">
	        </form>
	    </div>
	    <div class="usermenus">
	      <ul class="usermenu">
	        <li class="usermenu-charge <?php if((isset($_GET['action']) && $_GET['action'] == 'charge') || !isset($_GET['action'])) echo 'active';?>"><a href="<?php echo add_query_arg('action','charge',get_permalink())?>"><i class="icon icon-money"></i> 在线充值</a></li>
	        <?php if(!_MBT('vip_hidden')){?><li class="usermenu-vip <?php if(isset($_GET['action']) && $_GET['action'] == 'vip') echo 'active';?>"><a href="<?php echo add_query_arg('action','vip',get_permalink())?>"><i class="icon icon-crown"></i> 升级 VIP</a></li><?php }?>
	        <li class="usermenu-history <?php if(isset($_GET['action']) && $_GET['action'] == 'history') echo 'active';?>"><a href="<?php echo add_query_arg('action','history',get_permalink())?>"><i class="icon icon-wallet"></i> 充值记录</a></li>
	        <?php if(plugin_check_cred() && get_option('erphp_mycred') == 'yes'){ $mycred_core = get_option('mycred_pref_core');?>
	        <li class="usermenu-mycred <?php if(isset($_GET['action']) && $_GET['action'] == 'mycred') echo 'active';?>"><a href="<?php echo add_query_arg('action','mycred',get_permalink())?>"><i class="icon icon-gift"></i> 积分记录</a></li>
	    	<?php }?>
	        <li class="usermenu-order <?php if(isset($_GET['action']) && $_GET['action'] == 'order') echo 'active';?>"><a href="<?php echo add_query_arg('action','order',get_permalink())?>"><i class="icon icon-order"></i> 购买清单</a></li>
	        <li class="usermenu-aff <?php if(isset($_GET['action']) && $_GET['action'] == 'aff') echo 'active';?>"><a href="<?php echo add_query_arg('action','aff',get_permalink())?>"><i class="icon icon-aff"></i> 我的推广</a></li>
	        <?php if(_MBT('withdraw')){?>
	        <li class="usermenu-withdraw <?php if(isset($_GET['action']) && $_GET['action'] == 'withdraw') echo 'active';?>"><a href="<?php echo add_query_arg('action','withdraw',get_permalink())?>"><i class="icon icon-withdraw"></i> 站内提现</a></li>
	        <li class="usermenu-withdraws <?php if(isset($_GET['action']) && $_GET['action'] == 'withdraws') echo 'active';?>"><a href="<?php echo add_query_arg('action','withdraws',get_permalink())?>"><i class="icon icon-withdraws"></i> 我的提现</a></li>
	    	<?php }?>
	        <li class="usermenu-user <?php if(isset($_GET['action']) && $_GET['action'] == 'info') echo 'active';?>"><a href="<?php echo add_query_arg('action','info',get_permalink())?>"><i class="icon icon-info"></i> 我的资料</a></li>
			<li class="usermenu-comments <?php if(isset($_GET['action']) && $_GET['action'] == 'comment') echo 'active';?>"><a href="<?php echo add_query_arg('action','comment',get_permalink())?>"><i class="icon icon-comments"></i> 我的评论</a></li>
			<li class="usermenu-post <?php if(isset($_GET['action']) && $_GET['action'] == 'post') echo 'active';?>"><a href="<?php echo add_query_arg('action','post',get_permalink())?>"><i class="icon icon-posts"></i> 我的投稿</a></li>
			<?php if(_MBT('post_collect')){?><li class="usermenu-collect <?php if(isset($_GET['action']) && $_GET['action'] == 'collect') echo 'active';?>"><a href="<?php echo add_query_arg('action','collect',get_permalink())?>"><i class="icon icon-stars"></i> 我的收藏</a></li><?php }?>
			<?php if(_MBT('ticket')){?>
			<li class="usermenu-ticket <?php if(isset($_GET['action']) && $_GET['action'] == 'ticket') echo 'active';?>"><a href="<?php echo add_query_arg('action','ticket',get_permalink())?>"><i class="icon icon-temp-new"></i> 提交工单</a></li>
			<li class="usermenu-tickets <?php if(isset($_GET['action']) && $_GET['action'] == 'tickets') echo 'active';?>"><a href="<?php echo add_query_arg('action','tickets',get_permalink())?>"><i class="icon icon-temp"></i> 我的工单</a></li>
			<?php }?>
	        <li class="usermenu-password <?php if(isset($_GET['action']) && $_GET['action'] == 'password') echo 'active';?>"><a href="<?php echo add_query_arg('action','password',get_permalink())?>"><i class="icon icon-lock"></i> 修改密码</a></li>
	        <li class="usermenu-signout"><a href="<?php echo wp_logout_url(get_bloginfo("url"));?>"><i class="icon icon-signout"></i> 安全退出</a></li>
	      </ul>
	    </div>
	  </div>
	  <div class="content" id="contentframe">
	    <div class="user-main">
	      <?php if(isset($_GET['action']) && $_GET['action'] == 'vip'){ ?>
	          <!---------------------------------------------------升级会员开始-->
	          <div class="charge vip">
	                <div class="charge-header">
	                	<h1><?php echo sprintf("%.2f",$okMoney);?></h1>
	                	<p class="desc">可用余额</p>
	                	<h3>
	                    <?php 
	                    $ciphp_year_price    = get_option('ciphp_year_price');
	                    $ciphp_quarter_price = get_option('ciphp_quarter_price');
	                    $ciphp_month_price  = get_option('ciphp_month_price');
	                    $ciphp_day_price  = get_option('ciphp_day_price');
	                    $ciphp_life_price  = get_option('ciphp_life_price');
	                    $userTypeId=getUsreMemberType();
	                    if($userTypeId==6){
	                        echo "您目前是VIP体验会员";
	                    }elseif($userTypeId==7){
	                        echo "您目前是VIP包月会员";
	                    }elseif ($userTypeId==8){
	                        echo "您目前是VIP包季会员";
	                    }elseif ($userTypeId==9){
	                        echo "您目前是VIP年费会员";
	                    }elseif ($userTypeId==10){
	                        echo "您目前是VIP终身会员";
	                    }else {
	                        echo '您未购买任何VIP服务';
	                    }
	                    echo ($userTypeId>0&&$userTypeId<10) ?'&nbsp;&nbsp;&nbsp;&nbsp;到期时间：'.getUsreMemberTypeEndTime() :'';
	                    ?>
	                	</h3>
	                </div>
	                <form>
	                	<div class="vip-items">
	                		<?php if($ciphp_day_price){?>
	                		<div class="item item-0">
	                			<div class="title">体验VIP</div>
	                			<div class="price"><?php echo $ciphp_day_price;?><span><?php echo $moneyName;?></span></div>
	                			<div class="time">1天</div>
	                			<?php echo _MBT('vip_day');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="6">立即升级</a>
	                		</div>
	                		<?php }?>
	                		<?php if($ciphp_month_price){?>
	                		<div class="item item-1">
	                			<div class="title">包月VIP</div>
	                			<div class="price"><?php echo $ciphp_month_price;?><span><?php echo $moneyName;?></span></div>
	                			<div class="time">1个月</div>
	                			<?php echo _MBT('vip_month');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="7">立即升级</a>
	                		</div>
	                		<?php }?>
	                		<?php if($ciphp_quarter_price){?>
	                		<div class="item item-2">
	                			<div class="title">包季VIP</div>
	                			<div class="price"><?php echo $ciphp_quarter_price;?><span><?php echo $moneyName;?></span></div>
	                			<div class="time">3个月</div>
	                			<?php echo _MBT('vip_quarter');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="8">立即升级</a>
	                		</div>
	                		<?php }?>
	                		<?php if($ciphp_year_price){?>
	                		<div class="item item-3">
	                			<div class="title">包年VIP</div>
	                			<div class="price"><?php echo $ciphp_year_price;?><span><?php echo $moneyName;?></span></div>
	                			<div class="time">12个月</div>
	                			<?php echo _MBT('vip_year');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="9">立即升级</a>
	                		</div>
	                		<?php }?>
	                		<?php if($ciphp_life_price){?>
	                		<div class="item item-4">
	                			<div class="title">终身VIP</div>
	                			<div class="price"><?php echo $ciphp_life_price;?><span><?php echo $moneyName;?></span></div>
	                			<div class="time">永久</div>
	                			<?php echo _MBT('vip_life');?>
	                			<a href="javascript:;" class="btn" evt="user.vip.submit" data-type="10">立即升级</a>
	                		</div>
	                		<?php }?>
	                	</div>
	                </form>
	          </div>
	          <!---------------------------------------------------升级会员结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'withdraw'){ $userAli=$wpdb->get_row("select * from ".$wpdb->iceget." where ice_user_id=".$current_user->ID);?>
	      	  <!---------------------------------------------------提现开始-->
	      	  <form>
	      	  <ul class="user-meta">
		  		<li><label>支付宝账号</label>
					<input type="text" class="form-control" id="ice_alipay" name="ice_alipay" value="<?php echo $userAli->ice_alipay;?>">
		  		</li>
		  		<li><label>支付宝姓名</label>
					<input type="text" class="form-control" id="ice_name" name="ice_name" value="<?php echo $userAli->ice_name;?>">
		  		</li>
		  		<li><label>提现比例</label>
					<?php echo get_option('ice_proportion_alipay').get_option('ice_name_alipay');?> = 1元
		  		</li>
		  		<li><label>手续费</label>
					<?php echo get_option("ice_ali_money_site")?>%
		  		</li>
		  		<li><label>提现<?php echo get_option('ice_name_alipay');?></label>
					<input type="text" class="form-control" id="ice_money" name="ice_money" value="">( 总资产：<?php echo $okMoney.' '.get_option('ice_name_alipay');?> )
		  		</li>
		  		<li>
					<input type="button" evt="withdraw.submit" class="btn btn-primary" value="我要提现">
					<input type="hidden" name="action" value="user.withdrawal">
		  		</li>
		  	  </ul>
		  	</form>
	      	  <!---------------------------------------------------提现结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'withdraws'){ ?>
	      	  <!---------------------------------------------------提现记录开始-->
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT count(ice_id) FROM $wpdb->iceget WHERE ice_user_id=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $wpdb->iceget where ice_user_id=".$current_user->ID." order by ice_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th width="20%"><?php echo $moneyName;?></th>
	          			<th width="30%" class="pc">实际到账（元）</th>
	                    <th width="30%">时间</th>
	                    <th width="20%">状态</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr>
	                  	<td><?php echo $value->ice_money;?></td>
	                  	<td class="pc"><?php echo ( (100-get_option("ice_ali_money_site")) * $value->ice_money / 100) / get_option('ice_proportion_alipay');?></td>
	                  	<td><?php echo $value->ice_time;?></td>
	                  	<td><?php if($value->ice_success == 1){echo '<span style="color:green">已完成</span>';}else{echo '处理中';}?></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	          <!---------------------------------------------------提现记录结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'history'){ ?>
	      	  <!---------------------------------------------------充值记录开始-->
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT count(*) FROM $wpdb->icemoney WHERE ice_success=1 and ice_user_id=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $wpdb->icemoney where ice_success=1 and ice_user_id=".$current_user->ID." order by ice_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr><th width="140">充值时间</th><th width="60">金额(<?php echo $moneyName;?>)</th><th width="180" class="pc">方式</th><th width="180" class=pc>状态</th></tr></thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr><td><?php echo $value->ice_time;?><br></td><td><dfn><?php echo $value->ice_money;?></dfn></td>
	                  <?php if(intval($value->ice_note)==0){echo "<td class=pc><font color=green>在线充值</font></td>\n";}elseif(intval($value->ice_note)==1){echo "<td class=pc>后台充值</td>\n";}elseif(intval($value->ice_note)==2){echo "<td class=pc><font color=blue>转账收款</font></td>\n";}elseif(intval($value->ice_note)==3){echo "<td class=pc><font color=orange>转账付款</font></td>\n";}elseif(intval($value->ice_note)==4){echo "<td class=pc><font color=orange>mycred兑换</font></td>\n";}elseif(intval($value->ice_note)==6){echo "<td class=pc><font color=orange>充值卡</font></td>\n";}else{echo '<td class=pc></td>';}?><td class=pc>成功</td></tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <div class="user-alerts">
	          	  <h4>充值常见问题：</h4>
	          	  <ul><li>付款后系统会与支付服务方进行交互读取数据，可能会导致到账延迟，一般不会超过2分钟。</li></ul>
	          </div>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	          <!---------------------------------------------------充值记录结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'mycred'){ ?>
	      	  <!---------------------------------------------------积分记录开始-->
	          <?php 
	          		$mycred_get_all_references = mycred_get_all_references();
			  	    $totallists = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix."myCRED_log WHERE user_id=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."myCRED_log where user_id=$current_user->ID order by time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th width="20%">行为</th>
	                    <th width="35%" class=pc>时间</th>
	                    <th width="10%">积分</th>
	                    <th width="35%" class=pc>条目</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){
	              ?>
	            	  <tr>
	            	  	<td><?php echo $mycred_get_all_references[$value->ref];?></td>
	                  	<td class=pc><?php echo date("Y-m-d H:i:s",$value->time);?></td>
	                  	<td><?php echo $value->creds;?></td>
	                  	<td class=pc><?php echo str_replace('%plural%', $mycred_core['name']['plural'], $value->entry);?></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess); ?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	          <!---------------------------------------------------积分记录结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'order'){ ?>
	      	  <!---------------------------------------------------下载清单开始-->
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$current_user->ID);
					$perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$current_user->ID order by ice_time DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th width="15%" class=pc>订单号</th>
	                    <th width="35%">商品名称</th>
	                    <th width="10%" class=pc>价格(<?php echo $moneyName;?>)</th>
	                    <th width="25%" class=pc>交易时间</th>
	                    <th width="15%">操作</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){
	              		$start_down = get_post_meta( $value->ice_post, 'start_down', true );
						$start_see = get_post_meta( $value->ice_post, 'start_see', true );
						$start_see2 = get_post_meta( $value->ice_post, 'start_see2', true );
						$start_down2 = get_post_meta( $value->ice_post, 'start_down2', true );
	              ?>
	            	  <tr>
	                  	<td class=pc><?php echo $value->ice_num;?><br></td><td><a target="_blank" href="<?php echo get_permalink($value->ice_post);?>"><?php echo get_post($value->ice_post)->post_title;?></a></td><td class=pc><?php echo $value->ice_price;?></td>
	                  	<td class=pc><?php echo $value->ice_time;?></td>
	                  	<?php if($start_down || $start_down2){?>
	                  	<td><a href="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/erphpdown/download.php?url='.$value->ice_url;?>" target="_blank">下载</a></td>
	                  	<?php }elseif($start_see || $start_see2){?>
	                  	<td><a href="<?php echo get_permalink($value->ice_post);?>" target="_blank">查看</a></td>
	                  	<?php }?>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	          <!---------------------------------------------------下载清单结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'aff'){ ?>
	      	  <!---------------------------------------------------我的推广开始-->
	          <div class="charge">
	          		<div class="charge-header">
	                	<h3>您的专属推广链接：<font color="#5bc0de"><?php bloginfo("url");?>/?aff=<?php echo $current_user->ID;?></font></h3>
	                </div>
			  </div>
	          <?php 
			  	    $totallists = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users WHERE father_id=".$current_user->ID);
			  	    $perpage = 15;
					$pagess = ceil($totallists / $perpage);
					if (!get_query_var('paged')) {
						$paged = 1;
					}else{
						$paged = $wpdb->escape(get_query_var('paged'));
					}
					$offset = $perpage*($paged-1);
					$lists = $wpdb->get_results("SELECT ID,user_login,user_registered FROM $wpdb->users where father_id=".$current_user->ID." order by user_registered DESC limit $offset,$perpage");
			  ?>
	          <?php if($lists) {?>
	          <table class="table table-striped table-hover user-orders">
	          	  <thead>
	              	  <tr>
	          			<th width="40%">用户</th>
	                    <th width="40%">注册时间</th>
	                    <th width="20%">消费额</th>
	                  </tr>
	              </thead>
	              <tbody>
	              <?php foreach($lists as $value){?>
	            	  <tr>
	                  	<td><?php echo $value->user_login;?></td>
	                  	<td><?php echo $value->user_registered;?></td>
	                  	<td><?php $tt = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$value->ID);echo $tt?$tt:"0";?></td>
	                  </tr>
			      <?php }?>
	              </tbody>
	          </table>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	          <div class="user-alerts">
	            <h4>推广说明：</h4>
	            <ul>
	                <li>请勿作弊，否则封相关账户不通知； </li>
	                <li>推广链接可以是任意页面后加 <span class="label label-info">?aff=<?php echo $current_user->ID;?></span>即可；</li>
	            </ul>
	            </div>
	          <!---------------------------------------------------我的推广结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'comment'){ ?>
	      	  <!---------------------------------------------------我的评论开始-->
	          <?php 
			  	$perpage = 10;
				if (!get_query_var('paged')) {
					$paged = 1;
				}else{
					$paged = $wpdb->escape(get_query_var('paged'));
				}
				$total_comment = $wpdb->get_var("select count(comment_ID) from $wpdb->comments where comment_approved='1' and user_id=".$current_user->ID);
				$pagess = ceil($total_comment / $perpage);
				$offset = $perpage*($paged-1);
				$results = $wpdb->get_results("select $wpdb->comments.comment_ID,$wpdb->comments.comment_post_ID,$wpdb->comments.comment_content,$wpdb->comments.comment_date,$wpdb->posts.post_title from $wpdb->comments left join $wpdb->posts on $wpdb->comments.comment_post_ID = $wpdb->posts.ID where $wpdb->comments.comment_approved='1' and $wpdb->comments.user_id=".$current_user->ID." order by $wpdb->comments.comment_date DESC limit $offset,$perpage");
				if($results){
			  ?>
	          <ul class="user-commentlist">
	            <?php foreach($results as $result){?>
	          	<li><time><?php echo $result->comment_date;?></time><p class="note"><?php echo $result->comment_content;?></p><p class="text-muted">文章：<a target="_blank" href="<?php echo get_permalink($result->comment_post_ID);?>"><?php echo $result->post_title;?></a></p></li>
	            <?php }?>
	          </ul>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无评论！</h6></div>
	          <?php }?>
	          <!---------------------------------------------------我的评论结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'post'){
	      		$totallists = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts WHERE post_author=".$current_user->ID." and post_status='publish' and post_type='post'");
				$perpage = 10;
				$pagess = ceil($totallists / $perpage);
				if (!get_query_var('paged')) {
					$paged = 1;
				}else{
					$paged = $wpdb->escape(get_query_var('paged'));
				}
				$offset = $perpage*($paged-1);
				$lists = $wpdb->get_results("SELECT * FROM $wpdb->posts where post_author=".$current_user->ID." and post_status='publish' and post_type='post' order by post_date DESC limit $offset,$perpage");
	      ?>
	      	  <?php if($lists) {?>
	          <ul class="user-postlist">
	          	<?php foreach($lists as $value){ $post = get_post($value->ID); setup_postdata($post);?>
	          	<li>
					<img class="thumb" src="<?php echo MBThemes_thumbnail();?>">
					<h2><a target="_blank" href="<?php the_permalink($value->ID);?>"><?php the_title();?></a></h2>
					<p class="note"><?php echo MBThemes_get_excerpt();?></p>
					<p class="text-muted"><?php echo $value->post_date;?></p>
				</li>
	          	<?php }?>
	          </ul>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无记录！</h6></div>
	          <?php }?>
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'collect'){ ?>
	      	  <!---------------------------------------------------我的收藏开始-->
	          <?php 
			  	$perpage = 10;
				if (!get_query_var('paged')) {
					$paged = 1;
				}else{
					$paged = $wpdb->escape(get_query_var('paged'));
				}
				$total_collect = $wpdb->get_var("select count(ID) from ".$wpdb->prefix."collects where user_id=".$current_user->ID);
				$pagess = ceil($total_collect / $perpage);
				$offset = $perpage*($paged-1);
				$results = $wpdb->get_results("select * from ".$wpdb->prefix."collects where user_id=".$current_user->ID." order by create_time DESC limit $offset,$perpage");
				if($results){
			  ?>
	          <ul class="user-commentlist">
	            <?php foreach($results as $result){?>
	          	<li><time><?php echo $result->create_time;?></time><p class="note"><a href="<?php the_permalink($result->post_id);?>" target="_blank"><?php echo get_the_title($result->post_id);?></a></p><p class="text-muted"><a href="javascript:;" class="article-collect" data-id="<?php echo $result->post_id;?>" title="取消收藏">取消收藏</a></li>
	            <?php }?>
	          </ul>
	          <?php MBThemes_custom_paging($paged,$pagess);?>
	          <?php }else{?>
	          <div class="user-ordernone"><h6>暂无收藏！</h6></div>
	          <?php }?>
	          <!---------------------------------------------------我的收藏结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'info'){ ?>
	      	  <!---------------------------------------------------我的资料开始-->
	          <?php $userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$current_user->ID);?>
	          <form style="margin-bottom: 30px">
	            <ul class="user-meta">
	              <li>
	                <label>用户名</label>
	                <?php echo $current_user->user_login;?> </li>
	              <li>
	                <label>账户</label>
	                已消费 <?php echo intval($userMoney->ice_get_money);?> , 剩余 <?php echo intval($okMoney);?></li>
	              <li>
	                <label>昵称</label>
	                <input type="input" class="form-control" name="nickname" value="<?php echo $current_user->nickname;?>">
	              </li>
	              <li>
	                <label>QQ</label>
	                <input type="input" class="form-control" name="qq" value="<?php echo get_user_meta($current_user->ID, 'qq', true);?>">
	              </li>
	              <li>
	                <label>个性签名</label>
	                <textarea class="form-control" name="description" rows="5" style="height: 80px;padding: 5px 10px;"><?php echo $current_user->description;?></textarea>
	              </li>
	              <li>
	                <input type="button" evt="user.data.submit" class="btn btn-primary" value="修改资料">
	                <input type="hidden" name="action" value="user.edit">
	              </li>
	            </ul>
	          </form>
	          <form style="margin-bottom: 30px">
	            <ul class="user-meta">
	            <li>
	                <label>邮箱</label>
	                <input type="email" class="form-control" name="email" value="<?php echo $current_user->user_email;?>">
	              </li>
	              <li>
	                <label>验证码</label>
	                <input type="text" class="form-control" name="captcha" value="" style="width:150px;display:inline-block"> <a evt="user.email.captcha.submit" style="display:inline-block;font-size: 13px;cursor: pointer;" id="captcha_btn">获取验证码</a>
	              </li>
	              <li>
	                <input type="button" evt="user.email.submit" class="btn btn-primary" value="修改邮箱">
	                <input type="hidden" name="action" value="user.email">
	              </li>               
	             </ul>
	          </form>

	          <?php if(_MBT('oauth_qq') || _MBT('oauth_weibo') || _MBT('oauth_weixin')){?>
	          	<ul class="user-meta">
				<li class="secondItem">
					<?php 
						$userSocial = $wpdb->get_row("select qqid,sinaid,weixinid from $wpdb->users where ID=".$current_user->ID);
					?>
					<label>社交账号绑定</label>
					<?php if(_MBT('oauth_weixin')){?>
					<section class="item">
						<section class="platform weixin">
							<i class="icon icon-weixin"></i>
						</section>
						<section class="platform-info">
							<p class="name">微信</p><p class="status">
							<?php if($userSocial->weixinid){?>
							<span>已绑定</span>
							<a href="javascript:;" evt="user.social.cancel" data-type="weixin">取消绑定</a>
							<?php }else{?>
							<a href="https://open.weixin.qq.com/connect/qrconnect?appid=<?php echo _MBT('oauth_weixinid');?>&redirect_uri=<?php bloginfo("url")?>/oauth/weixin/bind.php&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" >立即绑定</a>
							<?php }?>
							</p>
						</section>
					</section>
					<?php }?>
					<?php if(_MBT('oauth_weibo')){?>
					<section class="item">
						<section class="platform weibo">
							<i class="icon icon-weibo"></i>
						</section>
						<section class="platform-info">
							<p class="name">微博</p><p class="status">
							<?php if($userSocial->sinaid){?>
							<span>已绑定</span>
							<a href="javascript:;" evt="user.social.cancel" data-type="weibo">取消绑定</a>
							<?php }else{?>
							<a href="<?php bloginfo("url");?>/oauth/weibo/bind.php?rurl=<?php echo get_permalink(MBThemes_page('template/user.php'));?>?action=info" >立即绑定</a>
							<?php }?>
							</p>
						</section>
					</section>
					<?php }?>
					<?php if(_MBT('oauth_qq')){?>
					<section class="item">
						<section class="platform qq">
							<i class="icon icon-qq"></i>
						</section>
						<section class="platform-info">
							<p class="name">QQ</p><p class="status">
							<?php if($userSocial->qqid){?>
							<span>已绑定</span>
							<a href="javascript:;" evt="user.social.cancel" data-type="qq">取消绑定</a>
							<?php }else{?>
							<a href="<?php bloginfo("url");?>/oauth/qq/bind.php?rurl=<?php echo get_permalink(MBThemes_page('template/user.php'));?>?action=info" >立即绑定</a>
							<?php }?>
							</p>
						</section>
					</section>
					<?php }?>
				</li>
				</ul>
				<?php }?>
				<div class="user-alerts">
	          	  <h4>注意事项：</h4>
	          	  <ul>
	                      <li>请务必修改成你正确的邮箱地址，以便于忘记密码时用来重置密码。</li>
	                      <li>获取验证码时，邮件发送时间有时会稍长，请您耐心等待。</li>
	                 </ul>
	          </div>
	          <!---------------------------------------------------我的资料结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'password'){ ?>
	      	  <!---------------------------------------------------修改密码开始-->
	          <form>
	            <ul class="user-meta">
	              <li>
	                <label>原密码</label>
	                <input type="password" class="form-control" name="passwordold">
	              </li>
	              <li>
	                <label>新密码</label>
	                <input type="password" class="form-control" name="password">
	              </li>
	              <li>
	                <label>重复新密码</label>
	                <input type="password" class="form-control" name="password2">
	              </li>
	              <li>
	                <input type="button" evt="user.data.submit" class="btn btn-primary" value="修改密码">
	                <input type="hidden" name="action" value="user.password">
	              </li>
	            </ul>
	          </form>
	          <!---------------------------------------------------修改密码结束-->
	      <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'ticket'){ 
	      		if(function_exists('modown_ticket_new_html')){
	      			modown_ticket_new_html();
	      		}else{
	      			echo '您暂未购买此扩展功能，如需要请联系QQ82708210。';
	      		}
	      }elseif(isset($_GET['action']) && $_GET['action'] == 'tickets'){ 
	      		if(function_exists('modown_ticket_list_html')){
	      			modown_ticket_list_html();
	      		}else{
	      			echo '您暂未购买此扩展功能，如需要请联系QQ82708210。';
	      		}
	      }else{ 

	      		if(isset($_POST['paytype']) && $_POST['paytype']){
					$paytype=intval($_POST['paytype']);
					$doo = 1;
					
					if(isset($_POST['paytype']) && $paytype==1)
					{
						$url=constant("erphpdown")."payment/alipay.php?ice_money=".$_POST['ice_money'];
					}
					elseif(isset($_POST['paytype']) && $paytype==5)
					{
						$url=constant("erphpdown")."payment/f2fpay.php?ice_money=".$_POST['ice_money'];
					}
					elseif(isset($_POST['paytype']) && $paytype==4)
					{
						$url=constant("erphpdown")."payment/weixin.php?ice_money=".$_POST['ice_money'];
					}
					elseif(isset($_POST['paytype']) && $paytype==7)
					{
						$url=constant("erphpdown")."payment/paypy.php?ice_money=".esc_sql($_POST['ice_money']);
					}
					elseif(isset($_POST['paytype']) && $paytype==8)
					{
						$url=constant("erphpdown")."payment/paypy.php?ice_money=".esc_sql($_POST['ice_money'])."&type=alipay";
					}
					elseif(isset($_POST['paytype']) && $paytype==2)
					{
						$url=constant("erphpdown")."payment/paypal.php?ice_money=".$_POST['ice_money'];
					}
					elseif(isset($_POST['paytype']) && $paytype==18)
					{
						$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".$_POST['ice_money']."&type=2";
					}
					elseif(isset($_POST['paytype']) && $paytype==17)
					{
						$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".$_POST['ice_money']."&type=1";
					}elseif(isset($_POST['paytype']) && $paytype==13)
				    {
				        $url=constant("erphpdown")."payment/codepay.php?ice_money=".$_POST['ice_money']."&type=1";
				    }elseif(isset($_POST['paytype']) && $paytype==14)
				    {
				        $url=constant("erphpdown")."payment/codepay.php?ice_money=".$_POST['ice_money']."&type=3";
				    }elseif(isset($_POST['paytype']) && $paytype==15)
				    {
				        $url=constant("erphpdown")."payment/codepay.php?ice_money=".$_POST['ice_money']."&type=2";
				    }
					else{
						
					}
					if($doo) echo "<script>location.href='".$url."'</script>";
					exit;
				}
	      	?>
	          <!---------------------------------------------------在线充值开始-->
	          	<div class="charge">
	            	<div class="charge-header">
	                	<h1><?php echo sprintf("%.2f",$okMoney);?></h1>
	                	<p class="desc">可用余额</p>
	                </div>
	                <?php if(!_MBT('recharge_default')){?>
	            	<form id="charge-form" action="" method="post">
		              	<div class="item" style="overflow: hidden;margin-bottom:0">
		              		<?php if(_MBT('recharge_price_s')){
		              			$prices = _MBT('recharge_price');
		              			if($prices){
		              				$price_arr = explode(',',$prices);
		              				echo '<div class="prices">';
		              				foreach ($price_arr as $price) {
		              					echo '<input type="radio" name="ice_money" id="ice_money'.$price.'" value="'.$price.'" checked><label for="ice_money'.$price.'" evt="price.select">'.$price.'元</label>';
		              				}
		              				echo '</div>';
		              			}
		              		?>
		              		<input type="submit" value="立即充值" class="btn btn-recharge">
		              		<p style="margin-bottom:0">1 元 = <?php echo get_option('ice_proportion_alipay')?> <?php echo $moneyName;?></p>
		              		<?php }else{?>
			                <input type="number" min="0" step="0.01" class="form-control input-recharge" name="ice_money" required="" placeholder="1 元 = <?php echo get_option('ice_proportion_alipay')?> <?php echo $moneyName;?>"><input type="submit" value="立即充值" class="btn btn-recharge">
			            <?php }?>
			            </div>
			            <div class="item payment-radios">
		                    <?php if(get_option('ice_weixin_mchid')){?> 
		                    <input type="radio" id="paytype4" class="paytype" checked name="paytype" value="4" /> <label for="paytype4" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>
		                    <?php }?>
		                    <?php if(get_option('ice_ali_partner')){?> 
		                    <input type="radio" id="paytype1" class="paytype" checked name="paytype" value="1" /> <label for="paytype1" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label>
		                    <?php }?>
		                    <?php if(get_option('erphpdown_f2fpay_id')){?> 
		                    <input type="radio" id="paytype5" class="paytype" checked name="paytype" value="5" /> <label for="paytype5" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label>
		                    <?php }?>
			                <?php if(get_option('erphpdown_xhpay_appid32')){?> 
			                <input type="radio" id="paytype17" class="paytype" name="paytype" value="17" checked /> <label for="paytype17" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label> 
			                <?php }?>
			                <?php if(get_option('erphpdown_xhpay_appid31')){?> 
			                <input type="radio" id="paytype18" class="paytype" name="paytype" value="18" checked /> <label for="paytype18" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>   
			                <?php }?>
			                <?php if(get_option('erphpdown_codepay_appid')){?> 
			                <input type="radio" id="paytype13" class="paytype" name="paytype" value="13" checked /> <label for="paytype13" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label>
			                <input type="radio" id="paytype14" class="paytype" name="paytype" value="14" /> <label for="paytype14" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>
			                <input type="radio" id="paytype15" class="paytype" name="paytype" value="15" /> <label for="paytype15" class="payment-label payment-qqpay-label"><i class="icon icon-qq"></i></label>    
			                <?php }?>
			                <?php if(get_option('erphpdown_paypy_key')){?> 
			                <?php if(!_MBT('recharge_paypy_alipay')){?><input type="radio" id="paytype8" class="paytype" name="paytype" value="8" checked /> <label for="paytype8" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label><?php }?>
			                <input type="radio" id="paytype7" class="paytype" name="paytype" value="7" /> <label for="paytype7" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>  
			                <?php }?>
		                    <?php if(get_option('ice_payapl_api_uid')){?> 
		                    <input type="radio" id="paytype2" class="paytype" checked name="paytype" value="2" /> <label for="paytype2" class="payment-label payment-paypal-label"><i class="icon icon-paypal"></i></label> (美元汇率：<?php echo get_option('ice_payapl_api_rmb')?>)
		                     <?php }?> 
		                </div>
		            </form>
		            <?php }?>
		            <?php if(_MBT('user_charge_tips')){?><p class="charge-tips"><i class="icon icon-notice"></i> <?php echo _MBT('user_charge_tips')?></p><?php }?>
		            <?php if(function_exists("checkDoCardResult")){?>
		            <form id="charge-form2" action="" method="post">
		            	<h3><span>充值卡充值</span></h3>
		              	<div class="item">
			                <input type="text" class="form-control input-recharge" id="erphpcard_num" name="erphpcard_num" required="" placeholder="卡号">
			            </div>
			            <!--div class="item">
		                    <input type="password" class="form-control input-recharge" id="erphpcard_pass" name="erphpcard_pass" required="" placeholder="卡密">
		                </div-->
		                <div class="item">
			              	<input type="button" evt="user.charge.card.submit" value="立即充值" class="btn btn-card">
			            </div>
		            </form>
		            <?php }?>
		            <?php if(plugin_check_cred() && get_option('erphp_mycred') == 'yes'){?>
		            <form id="charge-form2" action="" method="post">
		            	<h3><span><?php echo $mycred_core['name']['plural'];?>兑换</span></h3>
		              	<div class="item">
			                <input type="number" min="0.01" step="0.01" class="form-control input-recharge" id="erphpmycred_num" name="erphpmycred_num" required="" placeholder="兑换<?php echo get_option('ice_name_alipay')?>的数量，<?php echo get_option('erphp_to_mycred').' '.$mycred_core['name']['plural'];?> = 1 <?php echo get_option('ice_name_alipay')?>"> 
			            </div>
		                <div class="item">
			              	<input type="button" evt="user.mycred.submit" value="立即兑换" class="btn btn-card">
			              	<p>可用<?php echo $mycred_core['name']['plural'];?>：<?php echo mycred_get_users_cred( $current_user->ID )?></p>
			            </div>
		            </form>
		            <?php }?>
	            </div>
	          
	          <!---------------------------------------------------在线充值结束-->
	      <?php }?>
	    </div>
	    <div class="user-tips"></div>
	  </div>
	</div>
	<script src="<?php bloginfo("template_url")?>/static/js/user.js"></script>
</div>