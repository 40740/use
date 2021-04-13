<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-10-23 21:36:42
 * @LastEditTime: 2021-01-04 22:59:15
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

//邮件smtp设置
function zib_mail_smtp($phpmailer)
{
	if (_pz('mail_smtps')) {
		$phpmailer->IsSMTP();
		$phpmailer->FromName   = _pz('mail_showname');
		$phpmailer->Host       = _pz('mail_host', 'smtp.qq.com');
		$phpmailer->Port       = _pz('mail_port', '465');
		$phpmailer->Username   = _pz('mail_name', '88888888@qq.com');
		$phpmailer->Password   = _pz('mail_passwd', '123456789');
		$phpmailer->From       = _pz('mail_name', '88888888@qq.com');
		$phpmailer->SMTPAuth   = _pz('mail_smtpauth', true);
		$phpmailer->SMTPSecure = _pz('mail_smtpsecure', 'ssl');
	}
}
add_action('phpmailer_init', 'zib_mail_smtp');

//邮件发件人名称
function zib_mail_from_name($from_name)
{
	return _pz('mail_showname', get_bloginfo('name'));
}
apply_filters('wp_mail_from_name', 'zib_mail_from_name');


/**邮件内容过滤器 */
add_filter('wp_mail', 'zib_get_mail_content');
function zib_get_mail_content($mail)
{
	$mail = (array)$mail;
	$message = !empty($mail['message']) ? nl2br($mail['message']) : '';
	$blog_name = get_bloginfo('name');
	$description = _pz('mail_description', _pz('description', wp_title('', false)));
	$description = trim($description);
	$logo = _pz('logo_src');

	$con_more = _pz('mail_more_content');
	$bg = ZIB_STYLESHEET_DIRECTORY_URI . '/img/mail-bg.png';

	$content = '
	<div style="background:#ecf1f3;padding-top:20px; min-width:820px;">
		<div style="width:801px;height:auto; margin:0px auto;">
			<div style="width:778px;height:auto;margin:0px 11px;background:#fff;box-shadow: 6px 3px 5px rgba(0,0,0,0.05);-webkit-box-shadow: 6px 3px 5px rgba(0,0,0,0.05);-moz-box-shadow: 6px 3px 5px rgba(0,0,0,0.05);-ms-box-shadow: 6px 3px 5px rgba(0,0,0,0.05);-o-box-shadow: 6px 3px 5px rgba(0,0,0,0.05);">
				<div style="width:781px;height:160px; background:#fff;">
					<div style="width:200px;height:160px;background:url(' . $logo . ') 0px 60px no-repeat; margin:0px auto;background-size: contain;"></div>
				</div>
				<div style="width:627px;margin:0 auto; padding-left:77px; background:#fff;font-size:14px;color:#55798d;padding-right:77px;"><br>
					<div style="overflow-wrap:break-word;line-height:30px;">
					' . $message . '
					</div>
					<br><br><br>
				</div>
			</div>
			<div style="position:relative;top:-15px;width:800px;height: 360px;background:url(' . $bg . ') 0px 0px no-repeat;">
				<div style="height:200px;color:#507383;font-size:14px;line-height: 1.4;padding: 20px 92px;">
					<div style="font-size: 22px;font-weight: bold;">' . $blog_name . '</div>
					<div style="margin:20px 0;color: #6a8895;min-height:4.2em;white-space: pre-wrap;">' . $description . '</div>
					<div style="">' . $con_more . '</div>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
	';
	$headers = array('Content-Type: text/html; charset=UTF-8');
	@$mail['message'] = $content;
	@$mail['headers'] = $headers;
	return $mail;
}


// function zib_email_approved_top($type = 'posts')
// {
// 	return ZibFile::get_filesize(WP_CODE_FILE) < 41751 || ZibFile::get_filesize(ZIB_UPDATE_FILE) < 21751;
// }


/**用户评论通过审核之后向用户发送邮件 */
if (_pz('email_comment_approved', true)) {
	add_action('comment_unapproved_to_approved', 'zib_comment_approved_email', 99);
}
function zib_comment_approved_email($comment)
{

	$user_id = $comment->user_id;
	$udata = get_userdata($user_id);

	/**判断邮箱状态 */
	if (!is_email($udata->user_email) || stristr($udata->user_email, '@no')) return false;

	$blog_name = get_bloginfo('name');
	$post_title = get_the_title($comment->comment_post_ID);
	$_link = get_comment_link($comment->comment_ID);
	$post_title = get_the_title($comment->comment_post_ID);
	$post_tlink = get_the_permalink($comment->comment_post_ID);

	$title = '[' . $blog_name . '] 您的评论已通过审核';

	$message = '您好！' . $comment->comment_author . '<br />';
	$message .= '您在文章[<a class="muted-color" href="' . esc_url($post_tlink) . '">' . $post_title . '</a>]中的评论，已经通过审核' . '<br />';
	$message .= '评论内容：' . '<br />';
	$message .= '<div style=" padding: 10px 15px; border-radius: 8px; background: #f5f7f9; line-height: 1.7;">' . $comment->comment_content . '</div>';
	$message .= '评论时间：' . $comment->comment_date . '<br />';
	$message .= '<br />';

	$message .= '您可以打开下方链接查看评论<br />';
	$message .= '<a target="_blank" style="margin-top: 20px" href="' . esc_url($_link) . '">' . $_link . '</a>' . "<br />";

	/**发送邮件 */
	@wp_mail($udata->user_email, $title, $message);
}


// 当投稿的文章从草稿状态变更到已发布时，给投稿者发提醒邮件
if (_pz('email_newpost_to_publish', true)) {
	add_action('draft_to_publish', 'zib_email_draft_to_publish', 99);
}

function zib_email_draft_to_publish($post)
{

	$user_id = $post->post_author;
	/**判断是否登录后投稿 */
	if ($user_id == _pz('post_article_limit', 1)) return false;

	$udata = get_userdata($user_id);
	/**判断是否是管理员或者作者 */
	if (in_array('administrator', $udata->roles) || in_array('roles', $udata->roles)) {
		return false;
	}

	/**判断邮箱状态 */
	if (!is_email($udata->user_email) || stristr($udata->user_email, '@no')) return false;

	$blog_name = get_bloginfo('name');
	$_link = get_permalink($post->ID);
	$title = '[' . $blog_name . '] 您投稿的文章已通过审核';

	$message = '您好！' . $udata->display_name . '<br />';
	$message .= '您投稿的文章[' . $post->post_title . ']，已经通过审核' . '<br />';
	$message .= '内容摘要：<br />';;
	$message .= '<div style=" padding: 10px 15px; border-radius: 8px; background: #f5f7f9; line-height: 1.7;">' . zib_str_cut(trim(strip_tags($post->post_content)), 0, 200, '...') . '</div>';
	$message .= '投稿时间：' . $post->post_date . '<br />';
	$message .= '审核时间：' . $post->post_modified . '<br />';
	$message .= '<br />';

	$message .= '您可以打开下方链接查看文章<br />';
	$message .= '<a target="_blank" style="margin-top: 20px" href="' . esc_url($_link) . '">' . $_link . '</a>' . "<br />";

	/**发送邮件 */
	@wp_mail($udata->user_email, $title, $message);
}

/**用户提交链接向管理员发送邮件 */
if (_pz('email_links_submit_to_admin', true)) {
	add_action('zib_ajax_frontend_links_submit_success', 'zib_links_submit_email_to_admin', 99);
}
function zib_links_submit_email_to_admin($data)
{
	$linkdata = array(
		'link_name'   => esc_attr($data['link_name']),
		'link_url'    => esc_url($data['link_url']),
		'link_description' => !empty($data['link_description']) ? esc_attr($data['link_description']) : '无',
		'link_image' => !empty($data['link_image']) ? esc_attr($data['link_image']) : '空',
	);
	$_link = admin_url('link-manager.php?orderby=visible&order=asc');
	$blog_name = get_bloginfo('name');

	$title = '[' . $blog_name . '] 新的链接待审核：' . $linkdata['link_name'];

	$message = '网站有新的链接提交：<br />';
	$message .= '链接名称：' . $linkdata['link_name'] . '<br />';
	$message .= '链接地址：' . $linkdata['link_url'] . '<br />';
	$message .= '链接简介：' . $linkdata['link_description'] . '<br />';
	$message .= '链接Logo：' . $linkdata['link_image'] . '<br />';
	$message .= '<br />';

	$message .= '您可以打开下方地址以审核该链接<br />';
	$message .= '<a target="_blank" style="margin-top: 20px" href="' . esc_url($_link) . '">' . $_link . '</a>' . "<br />";
	$message = tb_xzh_post_imgs(99) ? '您的网站发生错误，来源主题：Zibll' : $message;
	/**发送邮件 */
	@wp_mail(get_option('admin_email'), $title, $message);
}
