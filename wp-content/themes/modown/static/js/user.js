jQuery(function($){
	var ajax_url = _MBT.uri+"/action/user.php";
	var _tipstimer;

	function tips(str, hide=1){
		if( !str ) return false
			_tipstimer && clearTimeout(_tipstimer)
		$('.user-tips').html(str).animate({
			top: 0
		}, 220);
		if(hide){
			_tipstimer = setTimeout(function(){
				$('.user-tips').animate({
					top: -30
				}, 220)
			}, 5000);
		}
	}





		/* click event

		 * ====================================================

		 */

		 $(".prices label:last-child").addClass('active');

		 $(".checkin").click(function(){
			var that = $(this);
			layer.msg("签到中...");
			$.ajax({  
				type: 'POST',  
				url:  ajax_url,  
				dataType: 'json',
				data: {
					action: 'user.checkin',
				},
				success: function(data){  
					if( data.error ){
						if( data.msg ){
							layer.msg(data.msg)
						}
						return
					}
					layer.msg('签到成功！')
					that.addClass('active').text('已签到');
				}  

			});
			
			return false;
			
		});

		 $("#avatarphoto").change(function(){
	          $("#uploadphoto").ajaxSubmit({
	            dataType:  'json',
	            beforeSend: function() {
	              layer.msg('上传中...');	
	            },
	            uploadProgress: function(event, position, total, percentComplete) {

	            },
	            success: function(data) {
	              if (data == "1") {
	                layer.msg('头像修改成功');
	                location.reload();     
	              }else if(data == "2"){
	                layer.msg('图片大小至多100K');	
	                return false;
	              }else if(data == "3"){
	                layer.msg('图片格式只支持.jpg .png .gif');
	                return false;	
	              }else{
	                layer.msg('上传失败');	
	                return false;
	              }
	            },
	            error:function(xhr){
	              layer.msg('上传失败');	
	              return false;
	            }
	          });				   
			
	        });

		 $('.container-user').on('click', function(e){

		 	e = e || window.event;

		 	var target = e.target || e.srcElement

		 	var _ta = $(target)



		 	if( _ta.parent().attr('evt') ){

		 		_ta = $(_ta.parent()[0])

		 	}else if( _ta.parent().parent().attr('evt') ){

		 		_ta = $(_ta.parent().parent()[0])

		 	}



		 	var type = _ta.attr('evt')



		 	if( !type || _ta.hasClass('disabled') ) return 

		 		
		 		/* Theme by mobantu.com */
		 		switch( type ){

		 			case 'user.avatar.submit':
              			$("#avatarphoto").trigger("click");
                	break;

		 			case 'price.select':
		 				$(".prices label").removeClass('active');
		 				_ta.addClass('active');
		 			break;

		 			case 'user.data.submit':

		 			var form = _ta.parent().parent().parent()

		 			var inputs = form.serializeObject()



		 			var ispass = false

		 			if( inputs.action === 'user.password' ) ispass = true



		 				if( !inputs.action ){

		 					return

		 				}



		 				if( ispass ){

		 					if( !$.trim(inputs.passwordold) ){
		 						layer.msg('请输入原密码')
		 						return
		 					}

		 					if( !inputs.password || inputs.password.length < 6 ){

		 						layer.msg('新密码不能为空且至少6位')

		 						return

		 					}



		 					if( inputs.password !== inputs.password2 ){

		 						layer.msg('两次密码输入不一致')

		 						return

		 					}


		 				}else{



		 					if( !/.{2,20}$/.test(inputs.nickname) ){

		 						layer.msg('昵称限制在2-20字内')

		 						return

		 					}





		 					if( inputs.qq && !is_qq(inputs.qq) ){

		 						layer.msg('QQ格式错误')

		 						return

		 					}



		 				}

		 				layer.msg('修改中...')

		 				$.ajax({  

		 					type: 'POST',  

		 					url:  ajax_url,  

		 					data: inputs,  

		 					dataType: 'json',

						success: function(data){  

							if( data.error ){

								if( data.msg ){

									layer.msg(data.msg)

								}

								return

							}

							layer.msg('修改成功！')

							cache_userdata = null

							$('.user-meta:eq(1) input:password').val('')

						}  

					});  



		 				break;


		 				case 'user.email.submit':

		 				

		 				var form = _ta.parent().parent().parent()

		 				var inputs = form.serializeObject()

		 				

		 				if( !inputs.action ){

		 					return

		 				}

		 				

		 				if( !inputs.email ){

		 					layer.msg('邮箱不能为空')

		 					return

		 				}



		 				if( !is_mail(inputs.email) ){

		 					layer.msg('邮箱格式错误')

		 					return

		 				}

		 				

		 				if( !inputs.captcha ){

		 					layer.msg('请输入邮箱验证码')

		 					return

		 				}

		 				

		 				layer.msg('修改中...',0)

		 				$.ajax({  

		 					type: 'POST',  

		 					url:  ajax_url,  

							//data: inputs,  

							dataType: 'json',

							data: {

								action: inputs.action,

								captcha: inputs.captcha,

								email: inputs.email

							},

							success: function(data){  

								if( data.error ){

									if( data.msg ){

										layer.msg(data.msg)

									}

									return

								}

								

								layer.msg('邮箱修改成功！')

								location.reload();

							}  

						});

		 				

		 				break;

		 				

		 				case 'user.email.captcha.submit':

		 				

		 				var form = _ta.parent().parent().parent()

		 				var inputs = form.serializeObject()

		 				

		 				if( !inputs.action ){

		 					return

		 				}

		 				

		 				if( !inputs.email ){

		 					layer.msg('邮箱不能为空')

		 					return

		 				}



		 				if( !is_mail(inputs.email) ){

		 					layer.msg('邮箱格式错误')

		 					return

		 				}

		 				var captchabtn = $('#captcha_btn');

		 				

		 				if(captchabtn.hasClass("disabled")){

		 					layer.msg('您操作太快了，等等吧')

		 				}else{

		 					layer.msg('发送验证码中...')

		 					captchabtn.addClass("disabled");

		 					$.ajax({  

		 						type: 'POST',  

		 						url:  ajax_url,  

								//data: inputs,  

								dataType: 'json',

								data: {

									action: 'user.email.captcha',

									email: inputs.email

								},

								success: function(data){  

									if( data.error ){

										if( data.msg ){

											layer.msg(data.msg)

											captchabtn.removeClass("disabled");   

										}

										return

									}

									

									layer.msg('验证码已发送至新邮箱！')

									var countdown=60; 

									settime()

									function settime() { 

										if (countdown == 0) { 

											captchabtn.removeClass("disabled");   

											captchabtn.text("重新获取验证码");

											countdown = 60; 

											return;

										} else { 

											captchabtn.addClass("disabled");

											captchabtn.text("重新获取(" + countdown + ")"); 

											countdown--; 

										} 

										setTimeout(function() { settime() },1000) 

									}

								}  

							});

		 				}

		 				

		 				break;

		 				

		 				case 'user.charge.submit':

		 				var re = /^[1-9]+[0-9]*]*$/;

		 				if(document.getElementById("ice_money").value=="" || !re.test(document.getElementById("ice_money").value))

		 				{

		 					layer.msg("请输入充值金额");

		 					return false;

		 				}else{

		 					document.getElementById("charge-form").submit();	

		 				}

		 				break;

		 				case 'user.charge.card':
		 				
		 				$('#modal-pay').modal('show')
		 				break;
		 				
		 				case 'user.charge.card.submit':
		 				if(document.getElementById("erphpcard_num").value==""){
		 					layer.msg("请输入充值卡号");
		 					return false;
		 				}else{
		 					layer.msg('充值中...')
		 					$.ajax({  
		 						type: 'POST',  
		 						url:  ajax_url,  
		 						dataType: 'json',
		 						data: {
		 							action: 'user.charge.card',
		 							num: document.getElementById("erphpcard_num").value
		 						},
		 						success: function(data){  
		 							if( data.error ){
		 								if( data.msg ){
		 									layer.msg(data.msg)
		 								}
		 								return
		 							}
		 							layer.msg('充值成功')
		 							location.reload();
		 						}  
		 					}); 
		 				}
		 				break;

		 				case 'user.mycred.submit':
		 				if(document.getElementById("erphpmycred_num").value==""){
		 					layer.msg("请输入要兑换的数量");
		 					return false;
		 				}else{
		 					layer.msg('兑换中...')
		 					$.ajax({  
		 						type: 'POST',  
		 						url:  ajax_url,  
		 						dataType: 'json',
		 						data: {
		 							action: 'user.mycred',
		 							num: document.getElementById("erphpmycred_num").value
		 						},
		 						success: function(data){  
		 							if( data.error ){
		 								if( data.msg ){
		 									layer.msg(data.msg)
		 								}
		 								return
		 							}
		 							layer.msg('兑换成功')
		 							location.reload();
		 						}  
		 					}); 
		 				}
		 				break;

		 				case 'withdraw.submit':

		 				var form = _ta.parent().parent().parent()

		 				var inputs = form.serializeObject()

			                if( !inputs.ice_alipay ){
			                    layer.msg('支付宝账号不能为空')
			                    return
			                }

			                if( !inputs.ice_name ){
			                    layer.msg('支付宝姓名不能为空')
			                    return
			                }

			                if( !inputs.ice_money ){
			                    layer.msg('提现金额不能为空')
			                    return
			                }

			                layer.msg('申请中...')

				            $.ajax({  
				                type: 'POST',  
				                url:  ajax_url,  
				                data: {
				                	action: 'user.withdraw',
				                	ice_alipay: $("#ice_alipay").val(),
				                	ice_name: $("#ice_name").val(),
				                	ice_money: $("#ice_money").val()
				                },
				                dataType: 'json',
				                success: function(data){  

				                    if( data.error ){
				                        if( data.msg ){
				                            layer.msg(data.msg)
				                        }
				                        return
				                    }

				                    layer.msg('申请成功，请等待审核！')

				                    location.href=_MBT.usr+"?action=withdraws";
				                }  
				            });  

			            break;
		 				

		 				case 'user.vip.submit':
							layer.open({
							  title: '提示',
							  content: '确认升级VIP？',
							  yes: function(index, layero){
							  	layer.close(index);
				 				if(!_ta.hasClass('disabled')){
					 				_ta.addClass('disabled');
					 				layer.msg('升级中...')

					 				$.ajax({  

					 					type: 'POST',  

					 					url:  ajax_url,  

					 					dataType: 'json',

					 					data: {

					 						action: 'user.vip',

					 						userType: _ta.data("type"),

					 					},

					 					success: function(data){  

					 						if( data.error ){

					 							if(data.error == 3){
													layer.open({
													  type: 1,
													  skin: 'layui-layer-dialog',
													  title: '选择支付方式',
													  content: data.payment
													});
													jQuery('body').on("click",".erphpdown-type-link",function(){
														layer.closeAll();
													});
												}else{
													layer.msg(data.msg);
												}

					 							_ta.removeClass('disabled');

					 							return

					 						}

					 						layer.msg('升级成功')
					 						location.reload();

					 						cache_vipdata = null

					 					}  

					 				});  
					 			}
					 		}});
			 				return false;

		 				break;

		 				case 'user.social.cancel':
		 				layer.msg('解绑中...')
		 				var type = _ta.data('type');

	 					$.ajax({  

		 					type: 'POST',  

		 					url:  ajax_url,  

		 					dataType: 'json',

		 					data: {

		 						action: 'user.social.cancel',

		 						type: type,

		 					},

		 					success: function(data){  

		 						if( data.error ){

		 							if( data.msg ){

		 								layer.msg(data.msg)

		 							}

		 							return

		 						}

		 						layer.msg('解绑成功')
		 						location.reload();

		 					}  

		 				});  
		 				

		 			}

		 		})



});



function is_name(str) {    

	return /^[\w]{3,16}$/.test(str) 

}

function is_url(str) {

	return /^((http|https)\:\/\/)([a-z0-9-]{1,}.)?[a-z0-9-]{2,}.([a-z0-9-]{1,}.)?[a-z0-9]{2,}$/.test(str)

}

function is_qq(str) {

	return /^[1-9]\d{4,13}$/.test(str)

}

function is_mail(str) {

	return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(str)

}