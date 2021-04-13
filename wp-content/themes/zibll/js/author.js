/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:40
 * @LastEditTime: 2020-12-31 16:35:27
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

//社交帐号解绑
_win.bd.on("click", '.oauth-untying', function () {
    if (confirm("确认要解除帐号绑定吗？") == true) {
        var _this = $(this),
            data = {
                'action': 'user_oauth_untying',
                'user_id': _this.attr('user-id'),
                'type': _this.attr('untying-type'),
            };
        return zib_ajax(_this, data), !1;
    }
})


//提现模态框设置收款
_win.bd.on("click", '.rewards-tabshow', function () {
    $('.modal').modal('hide');
    $('a[href="#author-tab-rewards"]').tab('show');
})


_win.bd.on("click", '[tab-id]', function () {
    var tab = $(this).attr('tab-id');
    tab && $('a[href="#' + tab + '"]').tab('show');
})