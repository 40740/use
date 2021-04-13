/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-10-28 13:16:44
 * @LastEditTime  : 2020-10-28 13:42:57
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */


window.Clipboard = (function (window, document, navigator) {
    var textArea,
        copy;

    // 判断是不是ios端
    function isOS() {
        return navigator.userAgent.match(/ipad|iphone/i);
    }
    //创建文本元素
    function createTextArea(text) {
        textArea = document.createElement('textArea');
        textArea.value = text;
        document.body.appendChild(textArea);
    }
    //选择内容
    function selectText() {
        var range,
            selection;

        if (isOS()) {
            range = document.createRange();
            range.selectNodeContents(textArea);
            selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            textArea.setSelectionRange(0, 999999);
        } else {
            textArea.select();
        }
    }

    //复制到剪贴板
    function copyToClipboard(success, error) {
        try {
            if (document.execCommand("Copy")) {
                $.isFunction(success) ? success() : console.log("复制成功！");
            } else {
                $.isFunction(error) ? error() : console.error("复制失败，请手动复制！");
            }
        } catch (err) {
            $.isFunction(error) ? error() : console.error("复制错误！请手动复制！")
        }
        document.body.removeChild(textArea);
    }

    copy = function (text, success, error) {
        createTextArea(text);
        selectText();
        copyToClipboard(success, error);
    };

    return {
        copy: copy
    };
})(window, document, navigator);

$('body').on('click', "[data-clipboard-text]", function () {
    text = $(this).attr('data-clipboard-text');
    Clipboard.copy(text,function () {notyf("内容已复制")},function () {notyf("复制失败，请手动复制",'danger')} );
})