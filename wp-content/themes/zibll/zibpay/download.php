<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:50
 * @LastEditTime: 2020-12-24 20:22:16
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Email         : 770349780@qq.com
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

require dirname(__FILE__) . '/../../../../wp-load.php';

if (!isset($_GET['down_id']) || empty($_GET['post_id'])) {
    wp_safe_redirect(home_url());
}

//安全验证
if (!isset($_GET['key']) || !wp_verify_nonce($_GET['key'], 'pay_down')) {
    wp_die('资源获取失败');
    exit();
}

$down_id = $_GET['down_id'];
$post_id = $_GET['post_id'];

$down = zibpay_get_post_down_array($post_id);

if (empty($down[$down_id]['link'])) {
    wp_die('下载信息错误！');
    exit;
}
$file_dir = $down[$down_id]['link'];

$home = home_url('/');

if (stripos($file_dir, $home) === 0) {
    $file_dir = str_replace($home, "", $file_dir);
}

if (substr($file_dir, 0, 7) == 'http://' || substr($file_dir, 0, 8) == 'https://' || substr($file_dir, 0, 10) == 'thunder://' || substr($file_dir, 0, 7) == 'magnet:' || substr($file_dir, 0, 5) == 'ed2k:' || substr($file_dir, 0, 4) == 'ftp:') {
    $file_path = chop($file_dir);
    header('location:' . $file_path);
    //echo "<script type='text/javascript'>window.location='$file_path';</script>";
    exit;
}
$file_dir = chop($file_dir);
$file_dir = ABSPATH . $file_dir;

if (!file_exists($file_dir)) {
    wp_die('文件不存在！');
    exit;
}
$temp = explode("/", $file_dir);

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . end($temp) . "\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . filesize($file_dir));
ob_end_flush();
@readfile($file_dir);
exit;
