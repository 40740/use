<style>
.publish-config-box h3 {
	font-size: 16px;
	padding: 10px 10px;
	margin: 0;
	line-height: 1;
}
.config-table {
	background-color:#FFFFFF;
	font-size:14px;
	padding:15px 20px;
}
.config-table td{
	height:35px;
	padding-left:10px;
}
.config-input {
	width:320px;
}
.info-box h3 {
	font-size: 15px;
	padding: 10px 10px;
	margin: 0;
	line-height: 1;
}
.feature {
	padding-top:5px;
}
</style>
<?php
/**
保存处理
*/
$keydatas_password= 'keydatas.com';
$keydatas_title_unique=false;
$keydatas_tbk_link_enble=false;
$formSubmit = sanitize_text_field($_POST['formSubmit']);
if (isset($formSubmit) && $formSubmit != '') {
	if(check_admin_referer('keydatas_save_nonce') &&  current_user_can( 'manage_options' )){
		$keydatas_password = sanitize_text_field($_POST['keydatas_password']);
		$kds_title_unique = sanitize_text_field($_POST['keydatas_title_unique']);
		$keydatas_title_unique = isset($kds_title_unique) && $kds_title_unique=="true";
		$kds_tbk_link_enble = sanitize_text_field($_POST['keydatas_tbk_link_enble']);
		$keydatas_tbk_link_enble = isset($kds_tbk_link_enble) && $kds_tbk_link_enble=="true";
		update_option('keydatas_password', $keydatas_password);
		update_option('keydatas_title_unique', $keydatas_title_unique);
		update_option('keydatas_tbk_link_enble', $keydatas_tbk_link_enble);
		echo '<div id="message" class="updated fade"><p>保存成功</p></div>';
	}
}else{
    $keydatas_password = get_option('keydatas_password', $keydatas_password);
	$keydatas_title_unique = get_option('keydatas_title_unique', $keydatas_title_unique);
	$keydatas_tbk_link_enble = get_option('keydatas_tbk_link_enble', $keydatas_tbk_link_enble);
}
?>
<div class="wrap">
  <h2>简数采集和发布平台</h2>
  <div style="margin-left:20px;padding-top:10px;padding-bottom:10px;"><a href="http://www.keydatas.com" target="_blank">简数</a>是一个通用、简单、智能、在线的网页数据采集和发布平台，功能强大，操作简单。已获得广大WordPress用户的一致好评，保证你也会喜欢上它！
</div>
    <div class="publish-config-box">
      <h3>内容发布设置</h3>
      <div>
<form id="configForm" method="post" action="admin.php?page=keydatas/publish-setting.php">	  
        <table width="100%" class="config-table">
          <tr>
            <td width="15%">网站发布地址为:</td>
            <td><input type="text" id="homeUrl"  name="homeUrl" class="config-input" readonly value="<?php
                                if (isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) == "on") {
                                    echo "https://";
                                } else {
                                    echo "http://";
                                }
                                $domain = str_replace('\\', '/', $_SERVER['HTTP_HOST'] . str_replace('/wp-admin', '', dirname($_SERVER['SCRIPT_NAME'])));
                                echo $domain; ?>" />（采集和发布数据请到 <a href="http://dash.keydatas.com" target="_blank">简数控制台</a>）
            
            </td>
          </tr>
          <tr>
            <td>发布密码:</td>
            <td><input type="text" name="keydatas_password" class="config-input" value="<?php echo $keydatas_password; ?>" />（请注意修改并保管好）
            </td>
          </tr>
		  <tr>
			<td>根据标题去重:</td>
			<td><input type="checkbox" name="keydatas_title_unique" value="true" <?php if($keydatas_title_unique == true) echo "checked='checked'" ?> />存在相同标题，则不插入
			</td>
		</tr>
		  <tr style="display:none"><!-- 已不再支持 -->
			<td>淘宝客插件支持:</td>
			<td><input type="checkbox" name="keydatas_tbk_link_enble" value="true" <?php if($keydatas_tbk_link_enble == true) echo "checked='checked'" ?> />保存商品链接/推广链接到自定义栏目<code>tbk_link</code> （需要安装 <a href="https://wptao.com/wptao.html?affid=9034" target="_blank">WordPress淘宝客插件</a>）
			</td>
		</tr>					  
          <tr>
            <td><input type="submit"  name="formSubmit"  value="保存更改" class="button-primary" /></td>
            <td></td>
          </tr>
        </table>
    <?php
        wp_nonce_field('keydatas_save_nonce');
    ?>		
  </form>		
      </div>
    </div>
  <div class="info-box">
    <h3>简介和使用教程</h3>
    <div>
      <table width="100%" class="config-table">
        <tr>
          <td width="15%">简数官网:</td>
          <td><a href="http://www.keydatas.com" target="_blank">www.keydatas.com</a> &nbsp;&nbsp;&nbsp;&nbsp;QQ交流群：542942789</td>
        </tr>		
        <tr>
          <td>采集和发布教程：</td>
          <td><a href="http://doc.keydatas.com/getting-started.html" target="_blank" title="数据采集快速入门">采集快速入门</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.keydatas.com/publish/wordpress.html" target="_blank">数据发布到WordPress教程</a></td>
        </tr>
        <tr>
          <td>平台主要功能特性：</td>
          <td>
		  <div class="feature">1.采集不需安装任何客户端，<strong>在线可视化点选</strong>；</div>
		  <div class="feature">2.集成智能识别提取引擎,自动识别数据和规则，包括：翻页、标题，作者，发布日期，内容等,<strong>甚至不需修改即可开始采集</strong>;</div>
		  <div class="feature">3.图片下载支持存储到：阿里云OSS、七牛云、腾讯云;（支持水印、压缩等）</div>
		  <div class="feature">4.<strong>全自动化：定时采集+自动发布</strong>;</div>
		  <div class="feature">5.提供强大的<a href="http://doc.keydatas.com/task/seo.html" target="_blank" >SEO工具</a>，包括：<a href="http://doc.keydatas.com/task/seo/seo-tool/insert-dynamic-para.html" target="_blank" >正文插入动态段落(强烈推荐)</a>、正文插入段落及标题自动关键词、自动内链、同义词替换、简繁体转换、翻译等；</div>
		  <div class="feature">6.支持关键词泛采集;</div>
		  <div class="feature">7.与WordPress系统无缝结合，点击几下就可以发布到WordPress系统中。</div>
		  </td>
        </tr>		
      </table>
    </div>
  </div>
</div>