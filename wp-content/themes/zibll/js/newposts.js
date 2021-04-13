/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:40
 * @LastEditTime  : 2020-10-17 21:12:28
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */
//前台投稿
_win.bd.on("click", '.new-posts-submit', function () {
    var _this = $(this),
        type = _this.attr('action'),
        form = _this.parents('form'),
        data = form.serializeObject();
    data.action = type;
    data.post_content = tinyMCE.activeEditor.getContent();
    zib_ajax(_this, data, function (n) {
        n.url && $('.view-btn').html('<a target="_blank" href="' + n.url + '" class="but c-blue mr10"><i class="fa fa-file-text-o"></i> 预览文章</a>')
        n.time && $('.modified-time').html('<span class="badg">最后保存：' + n.time+'</div>');
        n.ok && $(".form-control").val("");
        n.singin && $('.signin-loader').click();
        if (n.open_url) {
            window.location.href = n.open_url;
            window.location.reload;
        }
    });
})