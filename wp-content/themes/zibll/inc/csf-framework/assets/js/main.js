/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-11 11:45:30
 * @LastEditTime: 2021-01-08 22:05:36
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题|Codestar Framework的自定义JS
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

(function ($, window, document, undefined) {
    jQuery(document).ready(function ($) {
        function cssTransition(n, t, o, i, e) {
            var r, a, s;
            i && (t += "px", o += "px", r = "translate3D(" + t + "," + o + " , 0)", a = {}, s = cssT_Support(), a[s + "transform"] = r, a[s + "transition"] = s + "transform 0s linear", a.cursor = e, "null" == i && (a[s + "transform"] = "", a[s + "transition"] = ""), n.css(a))
        }

        function cssT_Support() {
            var n = document.body || document.documentElement;
            n = n.style;
            return "" == n.WebkitTransition ? "-webkit-" : "" == n.MozTransition ? "-moz-" : "" == n.OTransition ? "-o-" : "" == n.transition ? "" : void 0
        }
        $.fn.minitouch = function (n) {
            n = $.extend({
                direction: "bottom",
                selector: "",
                depreciation: 50,
                onStart: !1,
                onEnd: !1
            }, n);
            var t = $(this),
                o = ($("body"), n.depreciation),
                i = 0,
                e = 0,
                r = 0,
                a = 0,
                s = 0,
                c = 0,
                u = 0,
                l = !1;
            t.on("touchstart pointerdown MSPointerDown", n.selector, function (n) {
                i = 0, e = 0, r = 0, a = 0, s = 0, c = 0, u = 0, i = n.originalEvent.pageX || n.originalEvent.touches[0].pageX, e = n.originalEvent.pageY || n.originalEvent.touches[0].pageY, l = !0
            }).on("touchmove pointermove MSPointerMove", n.selector, function (t) {
                r = t.originalEvent.pageX || t.originalEvent.touches[0].pageX, a = t.originalEvent.pageY || t.originalEvent.touches[0].pageY, c = r - i, u = a - e, s = 180 * Math.atan2(u, c) / Math.PI, "right" == n.direction && (u = 0, c = s > -40 && s < 40 && c > 0 ? c : 0), "left" == n.direction && (u = 0, c = (s > 150 || s < -150) && 0 > c ? c : 0), "top" == n.direction && (c = 0, u = s > -130 && s < -50 && 0 > u ? u : 0), "bottom" == n.direction && (c = 0, u = s > 50 && s < 130 && u > 0 ? u : 0), 0 === c && 0 === u || (t.preventDefault(), cssTransition($(this), c, u, l, "grab"))
            }).on("touchend touchcancel pointerup MSPointerUp", n.selector, function (p) {
                (Math.abs(c) > o || Math.abs(u) > o) && 0 != n.onEnd && n.onEnd(t), cssTransition($(this), 0, 0, "null", ""), l = !1, i = 0, e = 0, r = 0, a = 0, s = 0, c = 0, u = 0
            })
        };

        function ajax_submit(_this, _data, success, notice, e) {
            var form = _this.parents(".ajax-form");
            var _notice = form.find(".ajax-notice");
            var _tt = _this.html();
            var ajax_url = form.attr("ajax-url");
            var spin = '<i class="fa fa-spinner fa-spin fa-fw"></i> '
            var n_type = "warning";
            var n_msg = spin + '正在处理，请稍候...';
            _this.attr("disabled", true).html(spin + "请稍候...");
            if (notice) {
                _notice.html('<div style="padding: 10px;margin: 0;" class="notice"></div>');
                notice = spin + notice;
            }
            _notice.find('.notice').html(notice || n_msg).removeClass('notice-error notice-info').addClass('notice-warning');
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: _data,
                dataType: "json",
                error: function (n) {
                    n_con = '<div style="padding: 10px;margin: 0;" class="notice notice-error"><b>' + "网络异常或者操作失败，请稍候再试！ " + n.status + '|' + n.statusText + '</b></div>';
                    _notice.html(n_con);
                    _this.attr("disabled", false).removeClass('jb-blue').html('操作失败');
                    form.find('.progress').css('opacity', 0).find('.progress-bar').css({
                        'width': '0',
                        'transition': 'width .3s',
                    });
                },
                success: function (n) {
                    if (n.msg) {
                        n_type = n.error_type || (n.error ? "error" : "info");
                        n_con = '<div style="padding: 10px;margin: 0;" class="notice notice-' + n_type + '"><b>' + n.msg + '</b></div>';
                        _notice.html(n_con);
                    }
                    _this.attr("disabled", false).html(n.button || _tt);
                    if (n.reload) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $.isFunction(success) && success(n, _this, _data);
                }
            });
        }

        $("body").on("click", ".online-update", function () {
            var _data = {};
            var _this = $(this);
            var _progress = _this.parents(".ajax-form").find('.progress');
            if (_this.attr("disabled")) {
                return false;
            }
            _data.action = 'zibll_online_update';
            ajax_submit(_this, _data, function (n) {
                update_file(n);
            }, '正在获取更新文件，请稍候...');
            var update_file = function (n) {
                if (n.error) {
                    //出现错误
                    _this.attr("disabled", false).html('<i class="fa fa-cloud-download fa-fw"></i> 在线更新').siblings('.ajax-submit').fadeIn(150);
                    _progress.css('opacity', 0).find('.progress-bar').css({
                        'width': '0',
                        'transition': 'width .3s',
                    });
                } else {
                    //未出现错误
                    if (n.action) {
                        _data = n;
                        ajax_submit(_this, _data, function (n) {
                            update_file(n);
                        }, n.msg)
                    }
                    if (n.progress) {
                        //进度条
                        _this.attr("disabled", true).siblings('.ajax-submit').fadeOut(150);
                        _progress.css('opacity', 1);
                        _progress.find('.progress-bar').css({
                            'width': n.progress + '%',
                            'transition': 'width .5s',
                        })
                    }
                    if (n.progress_end && n.progress_time) {
                        //进度条
                        setTimeout(function () {
                            _progress.find('.progress-bar').css({
                                'width': n.progress_end + '%',
                                'transition': 'width ' + n.progress_time + 's',
                            })
                        }, 500);
                    }
                }
            }
        })

        $("body").on("click", ".ajax-submit", function () {
            var _data = {};
            var _this = $(this);
            var form = _this.parents(".ajax-form");
            if (_this.attr("disabled")) {
                return false;
            }
            form.find("input").each(function () {
                n = $(this).attr("ajax-name"), v = $(this).val();
                if (n) {
                    _data[n] = v;
                }
            });
            var is_aut = _this.attr('id') == 'authorization_submit' ? !0 : !1;
            return ajax_submit(_this, _data, function (n) {
                if (n.error && is_aut) {
                    _tt = '开启正版授权';
                    form.find('.hide-box').slideDown(300);
                }
            });
        })

        $(window).on('scroll resize', function () {
            var Top = document.documentElement.scrollTop + document.body.scrollTop;
            var ontop = $('.csf-container');

            Top > 300 ? ontop.addClass('sticky-sm') : ontop.removeClass('sticky-sm');
        })
        var _wid = $(window).width();

        $(document).on('click', '.csf-menu', function (e) {
            $('.csf-nav-options').toggleClass('show');
        })

        if (_wid < 783) {
            $('.csf-nav-options').minitouch({
                direction: 'right',
                onEnd: function (e) {
                    e.removeClass('show')
                }
            })
        }

    });
})(jQuery, window, document);