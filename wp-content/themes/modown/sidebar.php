<aside class="sidebar">
	<div class="theiaStickySidebar">
	<?php 
		if(is_singular()){
			if (function_exists('dynamic_sidebar') && dynamic_sidebar('widget_single_above')) : endif; 
		}
		if(is_singular() && wp_is_erphpdown_active() && (_MBT('down_position') == 'side' || _MBT('down_position') == 'sidetop' || _MBT('down_position') == 'sidebottom') && get_option('MBT_Modown_token') == md5(get_option('MBT_Modown_key'))){
			$start_down=get_post_meta(get_the_ID(), 'start_down', true);
			$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
			$start_see=get_post_meta(get_the_ID(), 'start_see', true);
			$days=get_post_meta(get_the_ID(), 'down_days', true);
			$price=get_post_meta(get_the_ID(), 'down_price', true);
			$url=get_post_meta(get_the_ID(), 'down_url', true);
			$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
			$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);
			$userType=getUsreMemberType();
			$vip = '';$vip2 = '';$vip3 = '';
			$downMsg = '';

			if($start_down){
				echo '<div class="widget widget-erphpdown">';
				if($price){
					if($memberDown != 4 && $memberDown != 8 && $memberDown != 9)
						echo '<div class="item price"><span>'.$price.'</span> '.get_option("ice_name_alipay").'</div>';
					else
						echo '<div class="item price"><span>VIP</span>专享</div>';
				}else{
					if($memberDown != 4 && $memberDown != 8 && $memberDown != 9)
						echo '<div class="item price"><span>免费</span></div>';
					else
						echo '<div class="item price"><span>VIP</span>专享</div>';
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
								$vip = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip-btn">升级VIP</a>';
							}
							if($userType < 9){
								$vip2 = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip-btn">升级VIP</a>';
							}
							if($userType < 10){
								$vip3 = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip-btn">升级VIP</a>';
							}
						}

						if($memberDown==3){
							echo '<div class="item vip">VIP可免费下载'.$vip.'</div>';
						}elseif($memberDown==2){
							echo '<div class="item vip">VIP可5折下载'.$vip.'</div>';
						}elseif($memberDown==5){
							echo '<div class="item vip">VIP可8折下载'.$vip.'</div>';
						}elseif($memberDown==6){
							echo '<div class="item vip">包年VIP可免费下载'.$vip2.'</div>';
						}elseif($memberDown==7){
							echo '<div class="item vip">终身VIP可免费下载'.$vip3.'</div>';
						}

						if($memberDown==4 && !$userType){
							echo '<div class="item vip vip-only">仅对VIP开放下载'.$vip.'</div>';
						}elseif($memberDown==8 && $userType < 9){
							echo '<div class="item vip vip-only">仅对年费VIP开放下载'.$vip2.'</div>';
						}elseif($memberDown==9 && $userType < 10){
							echo '<div class="item vip vip-only">仅对终身VIP开放下载'.$vip3.'</div>';
						}else{
							if($down_info){
								echo '<span class="erphpdown-icon-buy"><i>已购</i></span>';
								echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' target="_blank" class="down">立即下载</a>';
							}else{
								if ( ($memberDown==6 || $memberDown==8) && ($userType == 9 || $userType == 10)){
									echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
									echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' target="_blank" class="down">立即下载</a>';
								}elseif ( ($memberDown==7 || $memberDown==9) && $userType == 10){
									echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
									echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' target="_blank" class="down">立即下载</a>';
								}elseif( ($memberDown==3 || $memberDown==4) && $userType){
									echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
									echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().' target="_blank" class="down">立即下载</a>';
								}else{
									echo '<a href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' class="down erphp-box">立即购买</a>';
									if($days){
										echo '<div class="tips">（此内容购买后'.$days.'天内可下载）</div>';
									}
								}
							}
						}
					}else{
						if($memberDown==3){
							echo '<div class="item vip">VIP可免费下载'.$vip.'</div>';
						}elseif($memberDown==2){
							echo '<div class="item vip">VIP可5折下载'.$vip.'</div>';
						}elseif($memberDown==5){
							echo '<div class="item vip">VIP可8折下载'.$vip.'</div>';
						}elseif($memberDown==6){
							echo '<div class="item vip">包年VIP可免费下载'.$vip.'</div>';
						}elseif($memberDown==7){
							echo '<div class="item vip">终身VIP可免费下载'.$vip.'</div>';
						}elseif($memberDown==4 || $memberDown == 8 || $memberDown == 9){
							echo '<div class="item vip vip-only">仅对VIP开放下载'.$vip.'</div>';
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
		                        echo '<div class="meta">';
		                            echo '<span>' . $field['label'] . '：</span>';
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

				if(get_option('ice_tips')) echo '<div class="tips">'.get_option('ice_tips').'</div>';
				echo '</div>';
			}elseif($start_down2){
				echo '<div class="widget widget-erphpdown">';
				$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
				$wppay = new EPD(get_the_ID(), $user_id);
				if($wppay->isWppayPaid() || !$price){
					if($url){
						$downMsg .= '<h3>资源下载</h3>';
						$downList=explode("\r\n",$url);
						foreach ($downList as $k=>$v){
							$filepath = $downList[$k];
							if($filepath){
								$filepath = str_replace('：', ': ', $filepath);
								if(strpos($filepath,',')){
									$filearr = explode(',',$filepath);
									$arrlength = count($filearr);
									if($arrlength == 1){
										$downMsg.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='down'>点击下载</a></div>";
									}elseif($arrlength == 2){
										$downMsg.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='down'>点击下载</a></div>";
									}elseif($arrlength == 3){
										$downMsg.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='down'>点击下载</a>（".$filearr[2]."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr[2])."' href='javascript:;'>复制</a></div>";
									}
								}elseif(strpos($filepath,'  ')){
									$filearr = explode('  ',$filepath);
									$arrlength = count($filearr);
									if($arrlength == 1){
										$downMsg.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='down'>点击下载</a></div>";
									}elseif($arrlength >= 2){
										$filearr2 = explode(':',$filearr[0]);
										$filearr3 = explode(':',$filearr[1]);
										$downMsg.="<div class='item item2'>".$filearr2[0]."<a href='".$filearr2[1].':'.$filearr2[2]."' target='_blank' class='down'>点击下载</a>（提取码: ".$filearr3[1]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr3[1]."' href='javascript:;'>复制</a></div>";
									}
								}elseif(strpos($filepath,' ')){
									$filearr = explode(' ',$filepath);
									$arrlength = count($filearr);
									if($arrlength == 1){
										$downMsg.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='down'>点击下载</a></div>";
									}elseif($arrlength == 2){
										$downMsg.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='down'>点击下载</a></div>";
									}elseif($arrlength >= 3){
										$downMsg.="<div class='item item2'>".str_replace(':', '', $filearr[0])."<a href='".$filearr[1]."' target='_blank' class='down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
									}
								}else{
									$downMsg.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='down'>点击下载</a></div>";
								}
							}
						}
						echo $downMsg;
					}else{
						echo '<style>.widget-erphpdown{display:none !important;}</style>';
					}
				}else{
					if($memberDown == 3 && $userType){
						if($url){
							$downMsg .= '<h3>资源下载</h3>';
							$downList=explode("\r\n",$url);
							foreach ($downList as $k=>$v){
								$filepath = $downList[$k];
								if($filepath){
									$filepath = str_replace('：', ': ', $filepath);
									if(strpos($filepath,',')){
										$filearr = explode(',',$filepath);
										$arrlength = count($filearr);
										if($arrlength == 1){
											$downMsg.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='down'>点击下载</a></div>";
										}elseif($arrlength == 2){
											$downMsg.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='down'>点击下载</a></div>";
										}elseif($arrlength == 3){
											$downMsg.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='down'>点击下载</a>（".$filearr[2]."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr[2])."' href='javascript:;'>复制</a></div>";
										}
									}elseif(strpos($filepath,'  ')){
										$filearr = explode('  ',$filepath);
										$arrlength = count($filearr);
										if($arrlength == 1){
											$downMsg.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='down'>点击下载</a></div>";
										}elseif($arrlength >= 2){
											$filearr2 = explode(':',$filearr[0]);
											$filearr3 = explode(':',$filearr[1]);
											$downMsg.="<div class='item item2'>".$filearr2[0]."<a href='".$filearr2[1].':'.$filearr2[2]."' target='_blank' class='down'>点击下载</a>（提取码: ".$filearr3[1]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr3[1]."' href='javascript:;'>复制</a></div>";
										}
									}elseif(strpos($filepath,' ')){
										$filearr = explode(' ',$filepath);
										$arrlength = count($filearr);
										if($arrlength == 1){
											$downMsg.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='down'>点击下载</a></div>";
										}elseif($arrlength == 2){
											$downMsg.="<div class='item item2'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='down'>点击下载</a></div>";
										}elseif($arrlength >= 3){
											$downMsg.="<div class='item item2'>".str_replace(':', '', $filearr[0])."<a href='".$filearr[1]."' target='_blank' class='down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
										}
									}else{
										$downMsg.="<div class='item item2'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='down'>点击下载</a></div>";
									}
								}
							}
							echo $downMsg;
						}else{
							echo '<style>.widget-erphpdown{display:none !important;}</style>';
						}
					}else{
						if($memberDown == 3){
							echo '<div class="item price"><span>'.$price.'</span> 元</div><div class="item vip">VIP免费查看/下载<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip-btn">升级VIP</a></div><a href="javascript:;" class="down erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即支付</a>';
						}else{
							echo '<div class="item price"><span>'.$price.'</span> 元</div><a href="javascript:;" class="down erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即支付</a>';
						}
					}
				}

				if(function_exists('get_field_objects')){
	                $fields = get_field_objects();
	                if( $fields ){
	                	echo '<div class="custom-metas">';
	                    foreach( $fields as $field_name => $field ){
	                    	if($field['value']){
		                        echo '<div class="meta">';
		                            echo '<span>' . $field['label'] . '：</span>';
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
	            
				if(get_option('ice_tips')) echo '<div class="tips">'.get_option('ice_tips').'</div>';
				echo '</div>';
			}elseif($start_see){
				echo '<div class="widget widget-erphpdown">';
				if($price){
					if($memberDown != 4 && $memberDown != 8 && $memberDown != 9)
						echo '<div class="item price"><span>'.$price.'</span> '.get_option("ice_name_alipay").'</div>';
					else
						echo '<div class="item price"><span>VIP</span>专享</div>';
				}else{
					if($memberDown != 4 && $memberDown != 8 && $memberDown != 9)
						echo '<div class="item price"><span>免费</span></div>';
					else
						echo '<div class="item price"><span>VIP</span>专享</div>';
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
								$vip = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip-btn">升级VIP</a>';
							}
							if($userType < 9){
								$vip2 = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip-btn">升级VIP</a>';
							}
							if($userType < 10){
								$vip3 = '<a href="'.get_permalink(MBThemes_page("template/user.php")).'?action=vip" target="_blank" class="erphpdown-vip-btn">升级VIP</a>';
							}
						}

						if($memberDown==3){
							echo '<div class="item vip">VIP可免费查看'.$vip.'</div>';
						}elseif($memberDown==2){
							echo '<div class="item vip">VIP可5折查看'.$vip.'</div>';
						}elseif($memberDown==5){
							echo '<div class="item vip">VIP可8折查看'.$vip.'</div>';
						}elseif($memberDown==6){
							echo '<div class="item vip">包年VIP可免费查看'.$vip2.'</div>';
						}elseif($memberDown==7){
							echo '<div class="item vip">终身VIP可免费查看'.$vip3.'</div>';
						}

						if($memberDown==4 && !$userType){
							echo '<div class="item vip vip-only">仅对VIP开放查看'.$vip.'</div>';
						}elseif($memberDown==8 && $userType < 9){
							echo '<div class="item vip vip-only">仅对年费VIP开放查看'.$vip2.'</div>';
						}elseif($memberDown==9 && $userType < 10){
							echo '<div class="item vip vip-only">仅对终身VIP开放查看'.$vip3.'</div>';
						}else{
							if($down_info){
								echo '<span class="erphpdown-icon-buy"><i>已购</i></span>';
							}else{
								if ( ($memberDown==6 || $memberDown==8) && ($userType == 9 || $userType == 10)){
									echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
								}elseif ( ($memberDown==7 || $memberDown==9) && $userType == 10){
									echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
								}elseif( ($memberDown==3 || $memberDown==4) && $userType){
									echo '<span class="erphpdown-icon-vip"><i>享免</i></span>';
								}else{
									echo '<a href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' class="down erphp-box">立即购买</a>';
									if($days){
										echo '<div class="tips">（此内容购买后'.$days.'天内可查看）</div>';
									}
								}
							}
						}
					}else{
						if($memberDown==3){
							echo '<div class="item vip">VIP可免费查看'.$vip.'</div>';
						}elseif($memberDown==2){
							echo '<div class="item vip">VIP可5折查看'.$vip.'</div>';
						}elseif($memberDown==5){
							echo '<div class="item vip">VIP可8折查看'.$vip.'</div>';
						}elseif($memberDown==6){
							echo '<div class="item vip">包年VIP可免费查看'.$vip.'</div>';
						}elseif($memberDown==7){
							echo '<div class="item vip">终身VIP可免费查看'.$vip.'</div>';
						}elseif($memberDown==4 || $memberDown == 8 || $memberDown == 9){
							echo '<div class="item vip vip-only">仅对VIP开放查看'.$vip.'</div>';
						}
						echo '<a href="javascript:;" class="down signin-loader">请先登录</a>';
					}
				}else{
					if(is_user_logged_in()){
						
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
		                        echo '<div class="meta">';
		                            echo '<span>' . $field['label'] . '：</span>';
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

				if(get_option('ice_tips')) echo '<div class="tips">'.get_option('ice_tips').'</div>';
				echo '</div>';
			}
		}
	?>

	<?php 
		if(get_post_type() == 'blog'){
			if (function_exists('dynamic_sidebar') && dynamic_sidebar('widget_blog')) : endif; 
		}elseif(is_archive()){
			if (function_exists('dynamic_sidebar') && dynamic_sidebar('widget_archive')) : endif; 
		}elseif(is_home()){
			if (function_exists('dynamic_sidebar') && dynamic_sidebar('widget_index')) : endif; 
		}else{
			if (function_exists('dynamic_sidebar') && dynamic_sidebar('widget_single')) : endif; 
		}
	?>
	</div>	    
</aside>