/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:40
 * @LastEditTime: 2020-12-31 23:25:30
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

$("link#swiper").length || $("head").append('<link type="text/css" id="swiper" rel="stylesheet" href="' + _win.uri + '/css/swiper.min.css">');
var _close = '';
/*	close +='<i class="icon-close"><i class="fa fa-play-circle-o" aria-hidden="true"></i></i>';
	close +='<i class="icon-close"><i class="fa fa-search-minus" aria-hidden="true"></i></i>';
	close +='<i class="icon-close"><i class="fa fa-search" aria-hidden="true"></i></i>';  */
_close += '<i class="icon-close"><i data-svg="close" data-class="ic-close" data-viewbox="0 0 1024 1024"></i></i>';

beijin = '<div class="modal-backdrop imgbox-bg"></div>';
anniu = '<div class="imgbox-an">' + _close + '</div>';
imgbox = '<div class="imgbox">' + beijin + anniu + '</div>';

_win.bd.append(imgbox);

var _i = $(".imgbox");
var _img = _i.find(".swiper-slide img");

_win.bd.on('click', '.img-close,.imgbox-bg,.imgbox-an .icon-close', function () {
	imgbox_close();
});

function imgbox_close(e) {
	$("html,body").css("overflow", ""), _i.removeClass("show click-show");
	"undefined" != typeof e && e.find("img,.swiper-zoom-container").css({
		transform: ""
	});
}

function imgbox_touch() {
	_i.find(".swiper-slide").minitouch({
		direction: 'bottom',
		selector: '.swiper-close.no-scale',
		depreciation: 100,
		onStart: false,
		onEnd: function (e) {
			imgbox_close(e)
		}
	});
}

function imgbox_open(e) {
	tbquire(['swiper'], function () {
		var _a = $(e),
			index = 0,
			tupian = '';

		_a.each(function () {
			index += 1;
			$(this).attr("imgbox-index", index);
			tupian += get_slide($(this));
		});

		wrapper = '<div class="swiper-wrapper">' + tupian + '</div>';
		swiper = '<div class="swiper-imgbox slider-imgbox">' + wrapper + '<div class="swiper-pagination"></div><div class="swiper-button-prev"></div><div class="swiper-button-next"></div></div>';
		_i.append(swiper);

		var imgbox_S = new Swiper(".slider-imgbox", {
			init: !1,
			autoplay: false,
			grabCursor: true,
			speed: 800,
			lazy: {
				loadPrevNext: !0
			},
			navigation: {
				nextEl: ".slider-imgbox .swiper-button-next",
				prevEl: ".slider-imgbox .swiper-button-prev"
			},
			pagination: {
				el: ".slider-imgbox .swiper-pagination",
				clickable: !0
			},
			zoom: {
				maxRatio: 2,
			},
			keyboard: {
				enabled: !0,
				onlyInViewport: !1
			},
			spaceBetween: 20,
			on: {
				init: function () {
					imgbox_touch()
				},
				zoomChange: function (i, e, n) {
					i > 1 ? _i.find(".swiper-close").removeClass("no-scale") : _i.find(".swiper-close").addClass("no-scale");
				}
			}
		});
		_win.bd.on('click', e, function () {
			$('.swiper-imgbox').css('display', '');
			return inx = $(this).attr("imgbox-index"), $("html,body").css("overflow", "hidden"),
				$(".modal").modal("hide"), imgbox_S.init(), imgbox_S.slideToLoop(inx - 1, 10), _i.addClass("show"),
				!1;
		});
		_win.bd.click('.imgbox-an .icon-play', function () {
			imgbox_S.autoplay.start()
		});
		_i.on('touchmove pointermove MSPointerMove', function (e) {
			e.preventDefault()
		});
	})
}

function get_slide(e) {
	var link = e.attr("href") || e.attr("src") || e.find('img').attr("src");
	var src = link.replace(/(.*\/)(.*)(-\d+x\d+)(.*)/g, "$1$2$4");
	src = src.replace(/\??x-oss-process(.*)/, "");
	return '<div class="swiper-slide"><div class="swiper-close no-scale"><div class="swiper-zoom-container"><div class="absolute img-close"></div><img data-src="' + src + '" class="swiper-lazy lazyload"><div class="swiper-lazy-preloader"></div></div></div></div>';
}

function click_imgbox(e) {
	var wrapper = '<div class="swiper-wrapper"><div class="swiper-slide"><div class="swiper-close no-scale"><div class="swiper-zoom-container"><div class="click-img"></div><div class="absolute img-close"></div></div></div></div></div>';
	var swiper = '<div class="click-imgbox swiper-imgbox">' + wrapper + '</div>';
	_i.find('.click-img').length || _i.append(swiper);

	_win.bd.on('click', e, function () {
		var _this = $(this);
		var a_href = _this.parent('a[data-imgbox="imgbox"]');
		if (a_href.length) return;

		var link = _this.attr("href") || _this.attr("src") || _this.find('img').attr("src");
		var src = link.replace(/(.*\/)(.*)(-\d+x\d+)(.*)/g, "$1$2$4");
		src = src.replace(/\??x-oss-process(.*)/, "");
		var img_html = '<img data-src="' + src + '" class="lazyload">';
		var img = _i.find('.click-img>img');
		if (img.length) {
			img.attr("src") != src && img.prop("outerHTML", img_html);
		} else {
			_i.find('.click-img').html(img_html + '<div class="swiper-lazy-preloader"></div>');
		}
		_i.delay(500).addClass("show click-show");
		imgbox_touch();
		$("html,body").css("overflow", "hidden");
		return !1;
	});
}
imgbox_open('a[data-imgbox="imgbox"]');
click_imgbox('.comt-main .box-img');
click_imgbox('.wp-posts-content img');
auto_fun();