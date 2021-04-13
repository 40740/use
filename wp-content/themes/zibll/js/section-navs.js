/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:40
 * @LastEditTime  : 2020-10-17 21:11:41
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */
var nt = '',
    angle = '',
    i = 0;
$('[data-nav] h1,[data-nav] h2,[data-nav] h3,[data-nav] h4').each(function () {
    var tag = $(this).prop("tagName"),
        text = $(this).text();
    if (i === 0) {
        nt += '<li class="n-' + tag + ' active"><a  class="text-ellipsis" href="#wznav_' + i + '">' + text + '</a></li>'
    } else {
        nt += '<li class="n-' + tag + '"><a  class="text-ellipsis" href="#wznav_' + i + '">' + text + '</a></li>'
    }
    $(this).attr('id', 'wznav_' + i)
    i++
})

$('.posts-nav-box').each(function () {
    _t = $(this).attr('data-title') || '';
    _t = _t && '<div class="box-body notop"><div class="title-theme">' + _t + '</div></div>';

    _box = '<div class="theme-box">' + _t + '\
                <div class="main-bg theme-box box-body radius8 main-shadow"><div class="posts-nav-lists scroll-y mini-scrollbar list-unstyled"></div></div>\
            </div>';
    $(this).append(_box);
});

$('.posts-nav-lists').html('<ul class="bl relative nav">' + nt + '</ul>');

_win.bd.on("click", '.posts-nav-lists a', function () {
    maxh_k();
    scrollTo($(this).attr("href"));
    return false;
})

$('body').scrollspy({
    target: '.posts-nav-lists',
    offset: 105
});

var a_n = $('.posts-nav-lists'),
    _hbl = $('.posts-nav-lists .bl').innerHeight();

if (_hbl > 380) {
    var n_hx = $('.posts-nav-lists .n-H1');
    n_hx.each(function () {
        if ($(this).nextUntil(".n-H1").length) {
            $(this).nextUntil(".n-H1").addClass('yc');
            $(this).append('<i class="fa fa-angle-right"></i>')
                .find('.fa').on('click', function () {
                    $(this).toggleClass('fa-rotate-90').parent().nextUntil(".n-H1").toggleClass('yc');
                })
        }
    })
}