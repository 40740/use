<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:36
 * @LastEditTime: 2021-01-03 13:43:57
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

/**
 * @description: 后台AJAX发送测试邮件
 * @param {*}
 * @return {*}
 */
function zib_test_send_mail()
{
    if (empty($_POST['email'])) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请输入邮箱帐号')));
        exit();
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo (json_encode(array('error' => 1, 'msg' => '邮箱格式错误')));
        exit();
    }
    $blog_name = get_bloginfo('name');
    $blog_url = get_bloginfo('url');
    $title = '[' . $blog_name . '] 测试邮件';

    $message = '您好！ <br />';
    $message .= '这是一封来自' . $blog_name . '[' . $blog_url . ']的测试邮件<br />';
    $message .= '该邮件由网站后台发出，如果非您本人操作，请忽略此邮件 <br />';
    $message .= current_time("Y-m-d H:i:s");

    try {
        $test = wp_mail($_POST['email'], $title, $message);
    } catch (\Exception $e) {
        echo array('error' => 1, 'msg' => $e->getMessage());
        exit();
    }
    if ($test) {
        echo (json_encode(array('error' => 0, 'msg' => '后台已操作')));
    } else {
        echo (json_encode(array('error' => 1, 'msg' => '发送失败')));
    }
    exit();
}
add_action('wp_ajax_test_send_mail', 'zib_test_send_mail');


//后台下载老数据
function zib_export_old_options()
{

    $nonce  = (!empty($_GET['nonce'])) ? sanitize_text_field(wp_unslash($_GET['nonce'])) : '';

    if (!wp_verify_nonce($nonce, 'export_nonce')) {
        die(esc_html__('安全效验失败！', 'csf'));
    }
    // Export
    header('Content-Type: application/json');
    header('Content-disposition: attachment; filename=zibll-old-options-' . date('Y-m-d') . '.json');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo json_encode(get_option('Zibll'));
    die();
}
add_action('wp_ajax_export_old_options', 'zib_export_old_options');



//后台下载老数据
function zib_test_send_sms()
{
    if (empty($_POST['phone_number'])) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '请输入手机号码')));
        exit();
    }

    echo json_encode(ZibSMS::send($_POST['phone_number'], '888888'));
    exit();
}
add_action('wp_ajax_test_send_sms', 'zib_test_send_sms');
