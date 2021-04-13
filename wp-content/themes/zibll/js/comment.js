/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:40
 * @LastEditTime: 2020-12-15 00:38:06
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

$comments = $('#comments-title');
$cancel = $('#cancel-comment-reply-link');
$author = $('#comment-user-info');
$submit = $('#commentform #submit');
$cancel = $('#cancel-comment-reply-link');
$com_ajax_url = _win.ajax_url;
$com_list = $('#postcomments .commentlist');

//回复评论按钮
_win.bd.on('click', '.comment-reply-link', function () {
	_this = $(this), commentid = _this.attr("data-commentid");
	return addComment.moveForm("div-comment-" + commentid, commentid, "respond", _this.attr("data-postid")),
		scrollTo(_this, -100),
		!1;
});
//编辑按钮
_win.bd.on('click', '.comment-edit-link', function () {
	_this = $(this), commentid = _this.attr("data-commentid");
	return addComment.moveForm("div-comment-" + commentid, commentid, "respond", _this.attr("data-postid"), 1),
		scrollTo($("#div-comment-" + commentid), -100),
		!1;
});
//删除评论
_win.bd.on('click', '.comment-trash-link', function () {
	var _this = $(this);
	var commentid = _this.attr("data-commentid");
	var trash_data = {
		action: 'trash_comment',
		comment_id: commentid,
	};
	if (confirm("确认要删除此评论吗？") == 1) {
		zib_ajax(_this, trash_data, function (n) {
			n.error || $('#comment-' + commentid).slideUp().delay(1000, function () {
				$(this).remove()
			});
		}, '正在获取内容...');
	}
});

//提交评论
$('#commentform').submit(function () {
	var _this = $(this);
	var inputs = _this.serializeObject();
	if ($author.length && $author.attr('require_name_email')) {
		if (inputs.author.length < 2 || inputs.email.length < 4) {
			return notyf('请输入昵称和邮箱', 'warning'),
				$author.addClass('open').find('[name="author"]').focus(), !1;
		}
		if (!is_mail(inputs.email)) {
			return notyf('邮箱格式错误', 'warning'),
				$author.addClass('open').find('[name="email"]').focus(), !1;
		}
	}
	if (inputs.comment.length < 6) {
		return notyf('评论内容过少', 'warning'),
			$('#comment').focus(), !1;
	}

	inputs.action = 'submit_comment';
	zib_ajax($submit, inputs, function (n) {
		var data = n.html;
		if (!n.error && data) {
			if (inputs.edit_comment_ID) {
				$cancel.click();
				$('#comment-' + inputs.edit_comment_ID).find('.comment-content').html(data);
			} else {
				data = data.replace(/class="comment/, 'style="display:none;" class="comment ajax-comment');
				data = inputs.comment_parent != 0 ? '<ul class="children">' + data + '</ul>' : data;
				respond = $('#respond');
				is_comment_parent = respond.parent().parent('.comment');
				if (is_comment_parent.length) {
					is_comment_parent.after(data);
				} else {
					if (!$com_list.length) {
						respond.after('<div id="postcomments"><ol class="commentlist list-unstyled">' + data + '</ol></div>');
					} else {
						$com_list.prepend(data);
					}
				}
				$cancel.click();
				$('#postcomments .ajax-comment').slideDown(200).removeClass('ajax-comment');
			}
			auto_fun();
			countdown();
			$('#comment').val('');
		}
	});
	return false;
});


var addComment = {
	moveForm: function (commId, parentId, respondId, postId, num) {
		var t = this,
			div, comm = t.I(commId),
			respond = t.I(respondId),
			cancel = t.I('cancel-comment-reply-link'),
			parent = t.I('comment_parent'),
			post = t.I('comment_post_ID');
		$('.comment-content,.comt-meta').show();
		if (num) {
			t.I('comment').value = '';
			$('#comment_parent').attr('name', 'edit_comment_ID');
			$submit.attr('disabled', true).html('<i class="loading mr6"></i><span>加载中</span>');
			$('#comment').attr('disabled', true);
			$('#' + commId).find('.comment-content,.comt-meta').hide();
			var data = {
				action: 'get_comment',
				comment_id: parentId,
			};
			zib_ajax(_this, data, function (n) {
				n.error && $cancel.click();
				$submit.html($submit_html).attr('disabled', false);
				$('#comment').attr('disabled', false).val(n.comment_content);
			}, '正在获取内容...')
		}

		t.respondId = respondId;
		postId = postId || false;

		if (!t.I('wp-temp-form-div')) {
			div = document.createElement('div');
			div.id = 'wp-temp-form-div';
			div.style.display = 'none';
			respond.parentNode.insertBefore(div, respond)
		}!comm ? (temp = t.I('wp-temp-form-div'),

			t.I('comment_parent').value = '0',
			temp.parentNode.insertBefore(respond, temp),
			temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);

		// pcsheight()
		if (post && postId) post.value = postId;
		parent.value = parentId;
		cancel.style.display = '';
		cancel.onclick = function () {
			var t = addComment,
				temp = t.I('wp-temp-form-div'),
				respond = t.I(t.respondId);
			$('#comment_parent').attr('name', 'comment_parent'),
				t.I('comment_parent').value = '0';
			$('.comment-content,.comt-meta').show()
			$('#comment').val('')
			if (temp && respond) {
				temp.parentNode.insertBefore(respond, temp);
				temp.parentNode.removeChild(temp)
			}
			this.style.display = 'none';
			this.onclick = null;
			return false
		};
		try {
			t.I('comment').focus()
		} catch (e) {}
		return false
	},
	I: function (e) {
		return document.getElementById(e)
	}
};

function exit_prev_edit() {
	$new_comm.show(), $new_sucs.show(), $("textarea").each(function () {
		this.value = "";
	}), edit = "";
}

var wait = 15,
	$submit_html = $submit.html();

function countdown() {
	if (wait > 0) {
		$submit.html('<div style="width:55px;"><i class="loading mr10"></i>' + wait + '</div>').attr('disabled', true);
		wait--;
		setTimeout(countdown, 1000)
	} else {
		$submit.html($submit_html).attr('disabled', false);
		wait = 15
	}
}