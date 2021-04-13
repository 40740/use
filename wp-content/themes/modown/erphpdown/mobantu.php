<?php
//by mobantu.com
function MBThemes_erphpdown_box(){
	global $post, $wpdb;
	$start_down=get_post_meta(get_the_ID(), 'start_down', true);
	$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
	$days=get_post_meta(get_the_ID(), 'down_days', true);
	$price=get_post_meta(get_the_ID(), 'down_price', true);
	$url=get_post_meta(get_the_ID(), 'down_url', true);
	$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
	$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);
	$nosidebar = get_post_meta(get_the_ID(),'nosidebar',true);
	$userType=getUsreMemberType();
	$vip = '';$vip2 = '';$vip3 = '';
	$downMsg = '';

	if($nosidebar || _MBT('down_position') == 'top' || _MBT('down_position') == 'sidetop' || _MBT('down_position') == 'bottom' || _MBT('down_position') == 'sidebottom') echo '<style>.erphpdown-box{display:block;}</style>';

	if($start_down){
		echo '<div class="erphpdown-box">';
		if($price){
			if($memberDown != 4 && $memberDown != 8 && $memberDown != 9){
				echo '<div class="item price"><t>下载价格：</t><span>'.$price.'</span> '.get_option("ice_name_alipay").'</div>';
			}else{
				echo '<div class="item price"><t>下载价格：</t><span>VIP专享</span></div>';
			}
		}else{
			if($memberDown != 4 && $memberDown != 8 && $memberDown != 9){
				echo '<div class="item price"><t>下载价格：</t><span>免费</span></div>';
			}else{
				echo '<div class="item price"><t>下载价格：</t><span>VIP专享</span></div>';
			}
		}
		if($price || $memberDown == 4 || $memberDown == 8 || $memberDown == 9){
			if(is_user_logged_in()){
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time desc");
				if($days > 0){
					$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
					$nowDate = date('Y-m-d H:i:s');
					if(strtotime($nowDate) > strtotime($lastDownDate)){
						$down_info = null;
					}
				}

				if(!$down_info){
					if(!$userType){
						$vip = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank">升级VIP</a>';
					}
					if($userType < 9){
						$vip2 = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank">升级VIP</a>';
					}
					if($userType < 10){
						$vip3 = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank">升级VIP</a>';
					}
				}

				if($memberDown==3){
					echo '<div class="item vip"><t>VIP优惠：</t>免费'.$vip.'</div>';
				}elseif($memberDown==2){
					echo '<div class="item vip"><t>VIP优惠：</t>5 折'.$vip.'</div>';
				}elseif($memberDown==5){
					echo '<div class="item vip"><t>VIP优惠：</t>8 折'.$vip.'</div>';
				}elseif($memberDown==6){
					echo '<div class="item vip"><t>VIP优惠：</t>包年VIP免费'.$vip2.'</div>';
				}elseif($memberDown==7){
					echo '<div class="item vip"><t>VIP优惠：</t>终身VIP免费'.$vip3.'</div>';
				}

				if($memberDown==4 && !$userType){
					echo '<div class="item vip vip-only">此资源仅对VIP开放下载'.$vip.'</div>';
				}elseif($memberDown==8 && $userType < 9){
					echo '<div class="item vip vip-only">此资源仅对年费VIP开放下载'.$vip2.'</div>';
				}elseif($memberDown==9 && $userType < 10){
					echo '<div class="item vip vip-only">此资源仅对终身VIP开放下载'.$vip3.'</div>';
				}else{
					if($down_info){
						echo '<span class="erphpdown-icon-buy"><i>已购</i></span>';
						echo '<a href='.constant("erphpdown").'download.php?url='.$down_info->ice_url.' target="_blank" class="down">立即下载</a>';
					}else{
						if (($memberDown==6 || $memberDown==8) && ($userType == 9 || $userType == 10)){
							echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
							echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' target="_blank" class="down">立即下载</a>';
						}elseif (($memberDown==7 || $memberDown==9) && $userType == 10){
							echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
							echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' target="_blank" class="down">立即下载</a>';
						}elseif( ($memberDown==3 || $memberDown==4) && $userType){
							echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
							echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' target="_blank" class="down">立即下载</a>';
						}else{
							echo '<a href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' class="down erphp-box">立即购买</a>';
							if($days){
								echo '<div class="tips">（此资源购买后'.$days.'天内可下载）</div>';
							}
						}
					}
				}
			}else{
				if($memberDown==3){
					echo '<div class="item vip"><t>VIP优惠：</t>免费'.$vip.'</div>';
				}elseif($memberDown==2){
					echo '<div class="item vip"><t>VIP优惠：</t>5 折'.$vip.'</div>';
				}elseif($memberDown==5){
					echo '<div class="item vip"><t>VIP优惠：</t>8 折'.$vip.'</div>';
				}elseif($memberDown==6){
					echo '<div class="item vip"><t>VIP优惠：</t>包年VIP免费'.$vip2.'</div>';
				}elseif($memberDown==7){
					echo '<div class="item vip"><t>VIP优惠：</t>终身VIP免费'.$vip3.'</div>';
				}elseif($memberDown==4 || $memberDown == 8 || $memberDown == 9){
					echo '<div class="item vip vip-only">此资源仅对VIP开放下载'.$vip.'</div>';
				}
				echo '<a href="javascript:;" class="down signin-loader">请先登录</a>';
			}
		}else{
			if(is_user_logged_in()){
				if($memberDown != 4 && $memberDown != 8 && $memberDown != 9){
					echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' target="_blank" class="down">立即下载</a>';
				}
			}else{
				echo '<a href="javascript:;" class="down signin-loader">请先登录</a>';
			}
		}
		if(function_exists('get_field_objects')){
            $fields = get_field_objects();
            if( $fields ){
            	echo '<div class="custom-metas">';
                foreach( $fields as $field_name => $field ){
                	if($field['value']){
                        echo '<div class="item">';
                            echo '<t>' . $field['label'] . '：</t>';
                            if(is_array($field['value'])){
                            	if($field['type'] == 'link'){
                            		echo '<a href="'.$field['value']['url'].'" target="'.$field['value']['target'].'">'.$field['value']['title'].'</a>';
                            	}else{
									echo implode(',', $field['value']);
								}
							}else{
								if($field['type'] == 'radio'){
									$vv = $field['value'];
									echo $field['choices'][$vv];
								}else{
									echo $field['value'];
								}
							}
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
        }

		if(get_option('ice_tips')) echo '<div class="item tips2"><t>下载说明：</t>'.get_option('ice_tips').'</div>';
		echo '</div>';
	}elseif($start_down2){
		echo '<div class="erphpdown-box erphpdown-box2">';
		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$wppay = new EPD(get_the_ID(), $user_id);
		if($wppay->isWppayPaid() || !$price){
			if($url){
				$downList=explode("\r\n",$url);
				foreach ($downList as $k=>$v){
					$filepath = $downList[$k];
					if($filepath){
						$filepath = str_replace('：', ': ', $filepath);
						if(strpos($filepath,',')){
							$filearr = explode(',',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 2){
								$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 3){
								$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2]."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr[2])."' href='javascript:;'>复制</a></div>";
							}
						}elseif(strpos($filepath,'  ')){
							$filearr = explode('  ',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength >= 2){
								$filearr2 = explode(':',$filearr[0]);
								$filearr3 = explode(':',$filearr[1]);
								$downMsg.="<div class='item item2'><t>".$filearr2[0]."</t><a href='".$filearr2[1].':'.$filearr2[2]."' target='_blank' class='erphpdown-down'>点击下载</a>（提取码: ".$filearr3[1]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr3[1]."' href='javascript:;'>复制</a></div>";
							}
						}elseif(strpos($filepath,' ')){
							$filearr = explode(' ',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 2){
								$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength >= 3){
								$downMsg.="<div class='item item2'><t>".str_replace(':', '', $filearr[0])."</t><a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
							}
						}else{
							$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}
					}
				}
				echo $downMsg;
			}else{
				echo '<style>#erphpdown{display:none !important;}</style>';
			}
		}else{
			if($memberDown == 3 && $userType){
				if($url){
					$downList=explode("\r\n",$url);
					foreach ($downList as $k=>$v){
						$filepath = $downList[$k];
						if($filepath){
							$filepath = str_replace('：', ': ', $filepath);
							if(strpos($filepath,',')){
								$filearr = explode(',',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength == 2){
									$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength == 3){
									$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2]."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr[2])."' href='javascript:;'>复制</a></div>";
								}
							}elseif(strpos($filepath,'  ')){
								$filearr = explode('  ',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength >= 2){
									$filearr2 = explode(':',$filearr[0]);
									$filearr3 = explode(':',$filearr[1]);
									$downMsg.="<div class='item item2'><t>".$filearr2[0]."</t><a href='".$filearr2[1].':'.$filearr2[2]."' target='_blank' class='erphpdown-down'>点击下载</a>（提取码: ".$filearr3[1]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr3[1]."' href='javascript:;'>复制</a></div>";
								}
							}elseif(strpos($filepath,' ')){
								$filearr = explode(' ',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength == 2){
									$downMsg.="<div class='item item2'><t>".$filearr[0]."</t><a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
								}elseif($arrlength >= 3){
									$downMsg.="<div class='item item2'><t>".str_replace(':', '', $filearr[0])."</t><a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
								}
							}else{
								$downMsg.="<div class='item item2'><t>文件".($k+1)."地址</t><a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}
						}
					}
					echo $downMsg;
				}else{
					echo '<style>#erphpdown{display:none !important;}</style>';
				}
			}else{
				if($url){
					$tname = '资源下载';
				}else{
					$tname = '内容查看';
				}
				if($memberDown == 3){
					echo $tname.'价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即支付</a>&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级VIP后免费<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip">立即升级</a>';
				}else{
					echo $tname.'价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="down erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即支付</a>';
				}
			}
		}
		if(function_exists('get_field_objects')){
            $fields = get_field_objects();
            if( $fields ){
            	echo '<div class="custom-metas">';
                foreach( $fields as $field_name => $field ){
                	if($field['value']){
                        echo '<div class="item">';
                            echo '<t>' . $field['label'] . '：</t>';
                            if(is_array($field['value'])){
                            	if($field['type'] == 'link'){
                            		echo '<a href="'.$field['value']['url'].'" target="'.$field['value']['target'].'">'.$field['value']['title'].'</a>';
                            	}else{
									echo implode(',', $field['value']);
								}
							}else{
								if($field['type'] == 'radio'){
									$vv = $field['value'];
									echo $field['choices'][$vv];
								}else{
									echo $field['value'];
								}
							}
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
        }
		if(get_option('ice_tips')) echo '<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
		echo '</div>';
	}
}


function MBThemes_erphpdown_download($msg,$pid){
	get_header();
?>
	<link rel="stylesheet" href="<?php echo constant("erphpdown"); ?>static/erphpdown.css" type="text/css" />
	<style>
	body{background: #f9f9f9}
	.banner{background-image: url(<?php echo bloginfo('template_url');?>/static/img/dlbg.jpg);}
	.banner:after{content: "";position: absolute;top: 0;bottom: 0;left: 0;right: 0;background: rgba(0,0,0,.5);z-index: 9;}
	.archive-title{margin-bottom:0}
	.content-wrap{margin-bottom: 10px;text-align: center;}
	.content{display: inline-block;max-width: 580px;text-align: left;width:100%}
	.single-content{padding:50px 30px;margin-bottom: 0;border-radius:0;box-shadow: inset 0px 15px 10px -15px #d4d4d4;}
	.article-content{margin-bottom: 0;font-size: 15px;}
	@media (max-width:620px){
		.single-content{padding:30px 20px;}
		.modown-erphpdown-bottom{padding:20px;}
	}
	</style>
	<div class="banner banner-archive">
		<div class="container">
			<h1 class="archive-title"><?php echo get_the_title($pid);?></h1>
		</div>
	</div>
	<div class="main">
		<div class="container">
			<div class="content-wrap">
		    	<div class="content">
		    		<article class="single-content">
		    			<span class="mbt-down-top"></span>
			    		<div class="article-content">
			    			<div class="erphpdown-msg">
			    				<?php echo $msg; ?>
			    			</div>
			            </div>
		            </article>
		            <div class="modown-erphpdown-bottom">
		            	<span class="line"></span>
		            	<?php if(_MBT('ad_erphpdown_s')){?>
		    			<div class="erphpdown-ad"><?php echo _MBT('ad_erphpdown');?></div>
		    			<?php }?>
		            </div>
		    	</div>
		    </div>
		</div>
	</div>
<?php
	get_footer();
	exit;
}


function getUserBuyTrue($uid,$pid){
	global $wpdb;
	$days=get_post_meta($pid, 'down_days', true);
	$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$pid."' and ice_success=1 and ice_user_id=".$uid." order by ice_time desc");
	if($days > 0){
		$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
		$nowDate = date('Y-m-d H:i:s');
		if(strtotime($nowDate) > strtotime($lastDownDate)){
			$down_info = null;
		}
	}
	if($down_info) 
		return 1;
	return 0;
}

function MBThemes_do_card($card){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$result = $wpdb->get_row("select * from $wpdb->erphpcard where card = '".$wpdb->escape($card)."'");
	if($result->status == '0'){
		$user_info=wp_get_current_user();
		$ss = $wpdb->query("update $wpdb->erphpcard set status=1,uid='".$user_info->ID."',usetime='".date("Y-m-d H:i:s")."' where card='".$wpdb->escape($card)."'");
		if($ss){
			$alipay_no = date("ymdhis").mt_rand(100, 999).mt_rand(100,999);
			$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
		VALUES ('".$result->price*get_option('ice_proportion_alipay')."','$alipay_no','".$user_info->ID."','".date("Y-m-d H:i:s")."',1,'6','".date("Y-m-d H:i:s")."','')";
			$a=$wpdb->query($sql);
			if($a){
				addUserMoney($user_info->ID, $result->price*get_option('ice_proportion_alipay'));
				return '1';
			}else{
				return '4';
			}
		}else{
			return '4';
		}
	}elseif($result->status == '1'){
		return '0';  //已被使用过
	}
	else{
		return '5';
	}
}