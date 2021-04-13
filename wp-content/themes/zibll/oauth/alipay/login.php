<?php
require dirname(__FILE__) . '/../../../../../wp-load.php';
//启用 session
session_start();
// 要求noindex
//wp_no_robots();

//获取后台配置
$Config = get_oauth_config('alipay');
$OAuth  = new \Yurun\OAuthLogin\Alipay\OAuth2($Config['appid'], $Config['appkey'], $Config['backurl']);
$OAuth->appPrivateKey = $Config['appkrivatekey'];

if ($Config['agent']) {
    $OAuth->loginAgentUrl = esc_url(home_url('/oauth/alipayagent'));
}
// 可选属性
/*
// 是否在登录页显示注册
$alipayOAuth->allowSignup = false;
*/

$url = $OAuth->getAuthUrl();
// 存储sdk自动生成的state，回调处理时候要验证
$_SESSION['YURUN_ALIPAY_STATE'] = $OAuth->state;
// 储存返回页面
$_SESSION['oauth_rurl']  = !empty($_GET["rurl"]) ? $_GET["rurl"] : '';

// 跳转到登录页
header('location:' . $url);
