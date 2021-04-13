/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:40
 * @LastEditTime: 2021-01-01 20:11:06
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。欢迎各位朋友与我相互交流。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */
/**
 * 图片上传封装插件
 */

var ToB64 = function (e, n) { //图片转Base64
        if ("undefined" == typeof FileReader) return notyf("当前浏览器不支持图片上传，请更换浏览器", "danger");
        var r = new FileReader();
        r.readAsDataURL(e[0]), r.onload = function (e) {
            n && n(e.target.result);
        };
    },
    jqXhr = function (fun) { //绑定上传进度
        jqXhr.onprogress = fun;
        //使用闭包实现监听绑
        return function () {
            //通过$.ajaxSettings.xhr();获得XMLHttpRequest对象
            var xhr = $.ajaxSettings.xhr();
            //判断监听函数是否为函数
            if (typeof jqXhr.onprogress !== 'function')
                return xhr;
            //如果有监听函数并且xhr对象支持绑定时就把监听函数绑定上去
            if (jqXhr.onprogress && xhr.upload) {
                xhr.upload.onprogress = jqXhr.onprogress;
            }
            return xhr;
        }
    };

//选择图片并预览
/**
 * zibupload="select_but" 选择图片的按钮，会有加载动画
 * .form-upload, .mini-upload form选择器
 * _this.attr('data-preview') || '.preview';  预览盒子
 * [auto-submit] 自动提交
 */
_win.bd.on("change", '[zibupload="image_upload"]', function (e) {
    var _this = $(this);
    var form = _this.parents('.form-upload, .mini-upload') || _this.parents('form');
    var in_b = form.find('[zibupload="select_but"]') || _this.siblings('.but');
    var _text = in_b.html();
    var r = this.files || e.dataTransfer.files;
    var max = Number(_win.up_max_size) || 2;
    var pre = _this.attr('data-preview') || '.preview';
    pre = form.find(pre);
    var loading = '<i class="loading mr6"></i>请稍候';
    _win.preview = pre.html();
    if (-1 == r[0].type.indexOf("image")) return notyf("选择的文件不是图像文件！", "danger"), _this.val('');
    if (max && (r[0].size > max * 1024000)) return notyf("所选图片大小不能超过" + max + "M，请重新选择", "danger"), _this.val('');
    if (!pre.length) return;
    pre.append('<div class="abs-center text-center fa-3x"><i class="loading"></i></div>');
    in_b.html(loading);
    ToB64(r, function (e) {
        pre.html('<img class="fit-cover" src="' + e + '">');
        in_b.html(_text);
        //自动提交
        form.find('[auto-submit]').click();
    });
});

var miniupload_ing = false;
//上传图片函数
/**[zibupload="submit"]  上传提交按钮
 * form = _this.parents('.form-upload, .mini-upload') || _this.parents('form'),
 * [zibupload="image_upload"]  图片的input
 * [zibupload="select_but"] 选择图片的按钮
 * data-tag 自定义文件name
 * input,[data-name] 循环插入_POST内容的选择器
 * zibupload-success  操作成功的执行函数名
 */
_win.bd.on("click", '[zibupload="submit"]', function (e) {
    if (miniupload_ing) {
        return notyf('正在处理中，请勿重复提交', 'warning', '2000');
    }
    if (e.preventDefault) e.preventDefault();
    else e.returnValue = false;

    var _this = $(this),
        form = _this.parents('.form-upload, .mini-upload') || _this.parents('form'),
        _text = _this.html(),
        formData = new FormData(),
        in_up = form.find('[zibupload="image_upload"]'),
        in_b = form.find('[zibupload="select_but"]') || in_up.siblings('.but');
    //循环插入文件
    var is_files = false;
    in_up.each(function () {
        tag = $(this).attr('data-tag') || 'file';
        fileObject = this.files[0];
        if (fileObject) {
            formData.append(tag, fileObject);
            is_files = true;
        }
    });
    if (!is_files) return notyf('请先选择图片！', "danger");
    //循环插入_POST内容
    form.find('input,[data-name]').each(function () {
        var _th = $(this);
        var n = _th.attr('name') || _th.attr('data-name');
        var v = _th.val() || _th.attr('data-value');
        if (v === undefined) {
            v = '';
        }
        if (n) {
            formData.append(n, v);
        }
    });

    var miniuploaded = function () {
        _this.attr('disabled', false).html(_text);
        in_b.attr('disabled', false);
        in_up.attr('disabled', false).val('');
        miniupload_ing = !1;
        _win.preview && form.find('.preview').html(_win.preview);
    }
    notyf('正在处理请稍等...', "load", "", "miniupload_ajax");
    _this.attr('disabled', true).html('<i class="loading mr3 c-white"></i><span class="loading-text c-white">上传中<count class="px12 ml3"></count></span><div class="progress progress-striped active"><div class="progress-bar progress-bar-success" role="progressbar" style="width:0;"></div></div>');
    in_up.attr('disabled', true);
    in_b.attr('disabled', true);
    miniupload_ing = true;
    $.ajax({
        url: _win.ajax_url,
        type: 'POST',
        data: formData,
        // 告诉jQuery不要去处理发送的数据
        processData: false,
        cache: false,
        // 告诉jQuery不要去设置Content-Type请求头
        contentType: false,
        dataType: 'json',
        error: function (n) {
            notyf("操作失败 " + n.status + ' ' + n.statusText + '，请刷新页面后重试', 'danger', '', 'miniupload_ajax');
            miniuploaded();
        },
        xhr: jqXhr(function (e) {
            var percent = Math.round(e.loaded / e.total * 100);
            _this.find('count').html(percent + '%');
            (percent >= 100) && _this.find('.loading-text').html('处理中 ...');
            form.find('.progress .progress-bar').css('width', percent + '%');
        }),
        success: function (n) {
            ys = (n.ys ? n.ys : (n.error ? 'danger' : ""));
            notyf(n.msg || '操作成功', ys, '', 'miniupload_ajax');
            miniuploaded();
            fun = _this.attr('zibupload-success');
            (fun && n.img_url) && (eval(fun + "(n,_this,form)"), (typeof fun == 'function' && fun(n, _this, form))); //执行额外函数
        }
    });
})